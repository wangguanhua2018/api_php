<?php
namespace app\models;

use yii\web\UploadedFile;
use app\components\Errors;
use yii;
//use yii\base\Model;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg, gif'],
        ];
    }
    /**
     * 判断指定目录是否存在否则创建指定目录
     * @param string $dir 指定目录路径
     */
    public function createDir($dir)
    {
        if (!is_dir($dir)) {
            @mkdir($dir);
        }

        return $dir;
    }
    /**
     * 上传文章中的图片信息
     */
    public function upload($model, $dir)
    {   
        // 文件验证失败
        if (!$this->validate()) {
            return [false, 
                Errors::ERROR_CODE_UPLOAD_FILE_FAIL, 
                $this->getModelError($model), 
                null
            ];
        }
        
        // 定义上传的目录路径
        $base = Yii::$app->basePath . '/web' . $dir;

        //判断路径是否存在否则创建上传路径
        $base = $this->createDir($base);
        
        // 定义保存的文件名称(以时间保存)
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $fileName = $msectime . '.' . $model->imageFile->extension;
        
        // 开始上传文件
        if (!$model->imageFile->saveAs($base . $fileName)) {
            return [false, 
                Errors::ERROR_CODE_UPLOAD_FILE_FAIL, 
                Errors::ERROR_MESSAGE_UPLOAD_FILE_FAIL, 
                null
            ];
        }
        
        // 返回文件路径
        $image = Yii::$app->request->hostInfo . $dir . $fileName;

        return [true, null, null, $image];
    }
}