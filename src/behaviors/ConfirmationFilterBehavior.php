<?php

namespace verbi\yii2ConfirmationFilter\behaviors;

use verbi\yii2Helpers\behaviors\base\Behavior;
use verbi\yii2Helpers\events\GeneralFunctionEvent;
use verbi\yii2WebController\behaviors\ActionMenuBehavior;
use verbi\yii2ConfirmationFilter\exceptions\NeedConfirmationException;
use Yii;
use yii\base\Action;
use yii\base\ActionEvent;
use yii\web\Controller;

class ConfirmationFilterBehavior extends Behavior {

    /**
     * @var array this property defines the actions that need confirmation, and
     * for which methods.
     *
     * You can use `'*'` to stand for all actions. When an action is explicitly
     * specified, it takes precedence over the specification given by `'*'`.
     *
     * For example,
     *
     * ```php
     * [
     *   'create' => ['get', 'post'],
     *   'update' => ['get', 'put', 'post'],
     *   'delete' => ['post', 'delete'],
     *   'custom'
     *   '*' => ['get'],
     * ]
     * ```
     */
    public $actions = [];
    public $message = 'Are you sure you want to perform this action?';
    private $messages = [];
    public $headerName = 'Accept-confirm';

    /**
     * @var bool Whether to register the asset bundle, overriding the base properties of Yii
     */
    public $registerAssetBundle = true;

    /**
     * Declares event handlers for the [[owner]]'s events.
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
    public function events() {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
            ActionMenuBehavior::EVENT_AFTER_GENERATE_CONFIG_FOR_ACTION_BUTTONS => 'afterGenerateConfigForActionButtons',
            ];
    }

    /**
     * @param ActionEvent $event
     * @return bool
     * @throws MethodNotAllowedHttpException when the request method is not allowed.
     */
    public function beforeAction($event) {
        $action = $event->action->id;
        if (isset($this->actions[$action])) {
            $verbs = $this->actions[$action];
        } elseif (array_search($action, $this->actions) !== false) {
            $verbs = null;
        } elseif (isset($this->actions['*'])) {
            $verbs = $this->actions['*'];
        } else {
            return $event->isValid;
        }

        $verb = Yii::$app->getRequest()->getMethod();
        if (
                (!is_array($verbs) || !in_array($verb, array_map('strtoupper', $verbs))) && Yii::$app->request->headers->get($this->headerName) != true && Yii::$app->request->get($this->headerName) != true && Yii::$app->request->post($this->headerName) != true
        ) {
            $event->isValid = false;
            $event->handled = true;

            throw new NeedConfirmationException('We need confirmation.');
        }
        return $event->isValid;
    }

    public function getMessage(Action $action) {
        $actionId = $action->id;
        if (isset($this->messages[$actionId])) {
            return $this->messages[$actionId];
        }
        return $this->message;
    }

    public function afterGenerateConfigForActionButtons(GeneralFunctionEvent $event) {
        $params = $event->getParams();
        $action = $params['action'];
        $data = [];
        if ((isset($this->actions[$action->id]) && is_array($this->actions[$action->id]))
        ) {
            $arr = $this->actions[$action->id];
            $message = $this->getMessage($action);
            if (sizeof($arr) && false === array_search($method, $arr)) {
                $data['confirm'] = $message;
                $data['params'] = array_merge(isset($data['params']) ? $data['params'] : [], [
                    $this->headerName => true,
                ]);
                $params['output']['linkOptions']['data']=$data;
            }
        } elseif (array_search($action->id, $this->actions) !== false) {
            $data['confirm'] = $this->getMessage($action);
            $data['params'] = array_merge(isset($data['params']) ? $data['params'] : [], [
                $this->headerName => true,
            ]);
            $params['output']['linkOptions']['data']=$data;
        }
    }
}
