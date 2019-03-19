<?php

namespace app\modules\test\controllers;


use app\components\CommonController;
/**
 * Default controller for the `test` module
 */
class DefaultController extends CommonController
{   
	public $modelClass = 'app\models\User';
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actions()
    {   
        $actions = parent::actions();
        unset($actions['delete'], $actions['create']);
        unset($actions['index']);
        //$actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }
    public function actionIndex()
    {
        return $this->generateResponseCheck(0, 'success', '123');
    }
}
