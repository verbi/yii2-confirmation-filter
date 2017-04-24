<?php
namespace verbi\yii2ConfirmationFilter\behaviors;

use verbi\yii2Helpers\behaviors\base\Behavior;

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
     *   '*' => ['get'],
     * ]
     * ```
     */
    public $actions = [];
    
    /**
     * Declares event handlers for the [[owner]]'s events.
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }
    
    /**
     * @param ActionEvent $event
     * @return bool
     * @throws MethodNotAllowedHttpException when the request method is not allowed.
     */
    public function beforeAction($event)
    {
        $action = $event->action->id;
        if (isset($this->actions[$action])) {
            $verbs = $this->actions[$action];
        } elseif (isset($this->actions['*'])) {
            $verbs = $this->actions['*'];
        } else {
            return $event->isValid;
        }
        
        
//        $action = $event->action->id;
//        if (isset($this->actions[$action])) {
//            $verbs = $this->actions[$action];
//        } elseif (isset($this->actions['*'])) {
//            $verbs = $this->actions['*'];
//        } else {
//            return $event->isValid;
//        }
//        $verb = Yii::$app->getRequest()->getMethod();
//        $allowed = array_map('strtoupper', $verbs);
//        if (!in_array($verb, $allowed)) {
//            $event->isValid = false;
//            // http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.7
//            Yii::$app->getResponse()->getHeaders()->set('Allow', implode(', ', $allowed));
//            throw new MethodNotAllowedHttpException('Method Not Allowed. This url can only handle the following request methods: ' . implode(', ', $allowed) . '.');
//        }
//        return $event->isValid;
    }
}