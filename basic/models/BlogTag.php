<?php

namespace app\models;

use Yii;
use app\components\Errors;
/**
 * This is the model class for collection "blog_tag".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $name
 * @property mixed $icon
 * @property mixed $create_time
 * @property mixed $update_time
 */
class BlogTag extends \yii\mongodb\ActiveRecord
{
    const SCENARIO_UPDATE = 'update';
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['blog', 'blog_tag'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'name',
            'icon',
            'create_time',
            'update_time',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'icon', 'create_time', 'update_time'], 'safe']
        ];
    }
    /**
     * 在新增的时候维护品牌的创建时间
     * 在更新的时候维护品牌的更新时间
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // 新增场景维护品牌的新增时间
        if ($insert) {
            $this->create_time = time();
            return true;
        }

        // 更新场景维护品牌的更新时间
        $this->update_time = time();

        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'name' => 'Name',
            'icon' => 'Icon',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
    /**
     * 新增或者编辑文章标签
     * @param array $params 标签信息
     * @param object $model 标签模型
     */
    public function updateTags($params, $model)
    {
        $model->load($params, '');
        if (!$model->save()) {
            return [false, Errors::ERROR_CODE_SAVE_FAIL, (new Model())->getModelError($model)];
        }

        return [true, Errors::ERROR_CODE_OK, Errors::ERROR_MESSAGE_OK];
    }
    /**
     * 获取文章标签列表
     */
    public function getListData($params)
    {
        // 获取查询条件
        $query = new \yii\mongodb\Query();
        $query = $query->select(['_id','name','create_time', 'update_time'])
               ->from(['blog','blog_tag'])
               ->where(['>', 'create_time', 0]);

        // 按标签名称搜素
        if (isset($params['name']) && !empty($params['name'])) {
            $query->andWhere(['like', 'name', $params['name']]);
        }

        // 返回分页列表信息
        $result = (new Model())->getPageListOnQuery($params, $query);

        // 处理id
        foreach ($result['list'] as $key => $item) {
            $result['list'][$key]['_id'] = (string) $result['list'][$key]['_id'];
        }
        return $result;
    }
    /**
     *  删除标签
     */
    public static function delTag($params)
    {
        if (!isset($params['id']) || empty($params['id'])) {
            return [false,
                Errors::ERROR_CODE_PARAMES_INCORECT,
                Errors::ERROR_MESSAGE_PARAMES_INCORECT
            ];
        }

        // 查找对应的模型
        $model  = static::find()->where(['_id' => $params['id']])->one();
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
}
