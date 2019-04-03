<?php
namespace app\models;

use Yii;
use app\components\Errors;

class MongodbModel extends \yii\mongodb\ActiveRecord
{
    /**
     *  删除模型数据
     */
    public function delModel($params)
    {
        if (!isset($params['_id']) || empty($params['_id'])) {
            return [false,
                Errors::ERROR_CODE_PARAMES_INCORECT,
                Errors::ERROR_MESSAGE_PARAMES_INCORECT
            ];
        }

        // 查找对应的模型
        $model  = self::find()->where(['_id' => $params['_id']])->one();
        if (empty($model)) {
            return [false,
                Errors::ERROR_CODE_DATA_NOT_EXIST,
                Errors::ERROR_MESSAGE_DATA_NOT_EXIST
            ];
        }

        // 删除对应的标签
        $model->delete();

        return [true,
            Errors::ERROR_CODE_OK,
            Errors::ERROR_MESSAGE_OK
        ];
    }
    /**
     * 新增或者编辑模型数据
     */
    public function updateModel($params, $model)
    {
        // 模型赋值
        $model->load($params, '');

        // 保存数据
        if (!$model->save()) {
            return [false,
                Errors::ERROR_CODE_SAVE_FAIL,
                (new Model())->getModelError($model)
            ];
        }

        // 操作成功
        return [true,
            Errors::ERROR_CODE_OK,
            Errors::ERROR_MESSAGE_OK
        ];
    }
}

