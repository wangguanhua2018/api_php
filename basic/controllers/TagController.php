<?php
namespace app\controllers;

use Yii;
use app\components\AuthController;
use app\components\Errors;
use app\models\BlogTag;

class TagController extends AuthController
{
    // 定义模型
    public $modelClass = 'app\models\BlogTag';
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete'], $actions['create']);
        unset($actions['index']);
        unset($actions['update']);
        return $actions;
    }
    /**
     * 获取文章标签列表
     */
    public function actionIndex()
    {
        // 获取客户端参数信息
        $params = $this->params;

        // 获取列表数据
        $list =  (new BlogTag())->getListData($params);

        return $this->generateResponseCheck(Errors::ERROR_CODE_OK, Errors::ERROR_MESSAGE_OK, $list);
    }
    /**
     * 新增文章标签
     */
    public function actionCreate()
    {
        // 获取客户端参数信息
        $params = $this->params;

        // 实例化模型:

        $model  = new BlogTag();
        // 存取权限数据
        list ($result, $code, $message) = $model->updateModel($params, $model);

        // 返回存取结果
        return $this->generateResponseCheck($code, $message);
    }
    /**
     * 编辑文章标签
     * @return array
     */
    public function actionEdit()
    {
        // 获取客户端参数信息
        $params = $this->params;
        if (!isset($params['id']) || empty($params['id'])) {
            return $this->generateResponseCheck(Errors::ERROR_CODE_PARAMES_INCORECT, Errors::ERROR_MESSAGE_PARAMES_INCORECT);
        }

        // 查找对应的模型
        $model  = BlogTag::find()->where(['_id' => $params['id']])->one();

        // 存取权限数据
        list ($result, $code, $message) = $model->updateModel($params, $model);

        // 返回存取结果
        return $this->generateResponseCheck($code, $message);
    }
    /**
     *  删除标签
     */
    public function actionDel()
    {
        // 获取客户端参数信息
        $params = $this->params;

        // 删除指定的标签
        list ($result, $code, $message) = (new BlogTag())->delModel($params);

        return $this->generateResponseCheck($code, $message);
    }
}