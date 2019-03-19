<?php
namespace app\controllers;

use Yii;
use app\components\CommonController;
use app\components\AuthController;
use app\components\Errors;
use app\models\UploadForm;
use yii\web\UploadedFile;

class ArticleController extends CommonController
{
    // 定义模型
    public $modelClass = 'app\models\UploadForm';
    /**
     * 后台用户登录
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
}





 