<?php

namespace verbi\yii2ConfirmationFilter\exceptions;

use Yii;
use yii\web\NotAcceptableHttpException;

class NeedConfirmationException extends NotAcceptableHttpException {

    public $controller;
    public $view;
    
    private $handled = false;
    
    public function __construct($message, $code = 406, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
//        $this->controller = Yii::$app->controller;
//        $className = $this->controller->className();
//        $this->controller->on($className::EVENT_AFTER_ACTION, [$this, 'endPage']);

        $this->view = Yii::$app->controller->getView();
        $className = $this->view->className();
        $this->view->on($className::EVENT_AFTER_RENDER, [$this, 'handle']);
    }

    public function handle($event) {
        if(!$this->handled) {
            $this->handled = true;
//            $response = Yii::$app->getResponse();
//            $response->statusCode = 406;
            $event->output .= $this->view->render('@vendor/verbi/yii2-confirmation-filter/src/views/confirmationFilter/confirmation', [
                'headerName' => $this->headerName,
                'actionName' => Yii::$app->controller->action->id,
                'returnUrl' => Yii::$app->getRequest()->referrer,
                'method' => Yii::$app->getRequest()->getMethod(),
                'get' => Yii::$app->getRequest()->get(),
                'post' => Yii::$app->getRequest()->post(),
            ]);
        }
    }

    public function getName() {
        return 'Please Confirm';
    }

}
