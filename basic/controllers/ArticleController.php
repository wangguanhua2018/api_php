<?php
namespace app\controllers;

use Yii;
use app\components\AuthController;
use app\components\Errors;
use app\models\UploadForm;
use app\models\BlogArticle;
use yii\web\UploadedFile;

class ArticleController extends AuthController
{
    // 定义模型
    public $modelClass = 'app\models\UploadForm';
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete'], $actions['create']);
        unset($actions['index']);
        unset($actions['update']);
        return $actions;
    }
    /**
     * 发布文章中上传文章中的图片
     */
    public function actionUpload()
    {
    	// 获取客户端传入的文件信息
        $uploadModel = new UploadForm();
        $uploadModel->imageFile = UploadedFile::getInstanceByName('img');
        
        // 定义上传的目录路径
        $dir = '/uploads/article/' . date('Y-m-d', time()) . '/';

        // 将文件传至服务器并且返回文件路径以及文件名
        list($result, $code, $message, $file) = $uploadModel->upload($uploadModel, $dir);
        if ($result === false) {
            return $this->generateResponseCheck($code, $message); 
        }
    	
        // 返回上传文件的路径
    	return $this->generateResponseCheck($code, $message, ['image' => $file]);
    }
    /**
     * 发布文章
     */
    public function actionCreate()
    {
        // 获取客户端参数信息
        $params = $this->params;

        // 实例化模型:
        $model  = new BlogArticle();

        // 存取权限数据
        list ($result, $code, $message) = $model->updateModel($params, $model);

        // 返回存取结果
        return $this->generateResponseCheck($code, $message);
    }
    /**
     * 编辑文章
     */
    public function actionEdit()
    {
        // 获取客户端参数信息
        $params = $this->params;

        // 实例化模型:
        $model  = BlogArticle::find()->where([
            '_id' => $params['_id']
        ])->one();
        if (empty($model)) {
            return $this->generateResponseCheck(Errors::ERROR_CODE_DATA_NOT_EXIST, Errors::ERROR_MESSAGE_DATA_NOT_EXIST);
        }
        // 存取权限数据
        list ($result, $code, $message) = $model->updateModel($params, $model);

        return $this->generateResponseCheck($code, $message);
    }
    /**
     * 获取文章列表
     */
    public function actionIndex()
    {
        // 获取客户端参数信息
        $params = $this->params;

        // 获取列表数据
        $list =  (new BlogArticle())->getListData($params);

        return $this->generateResponseCheck(Errors::ERROR_CODE_OK, Errors::ERROR_MESSAGE_OK, $list);
    }
    /**
     *  删除文章
     */
    public function actionDel()
    {
        // 获取客户端参数信息
        $params = $this->params;

        // 删除指定的标签
        list ($result, $code, $message) = (new BlogArticle())->delModel($params);

        return $this->generateResponseCheck($code, $message);
    }
}





 