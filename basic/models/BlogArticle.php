<?php

namespace app\models;

use Yii;
use app\components\Errors;
/**
 * This is the model class for collection "blog_article".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $category_id
 * @property mixed $content
 * @property mixed $title
 * @property mixed $describe
 * @property mixed $create_time
 * @property mixed $update_time
 */
class BlogArticle extends MongodbModel
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['blog', 'blog_article'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'category_id',
            'content',
            'title',
            'describe',
            'create_time',
            'update_time',
            'category_name'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'content', 'title', 'describe', 'create_time', 'update_time', 'category_name'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'category_id' => 'Category ID',
            'content' => 'Content',
            'title' => 'Title',
            'describe' => 'Describe',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
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
     * 获取文章列表
     */
    public function getListData($params)
    {
        // 获取查询条件
        $query = new \yii\mongodb\Query();
        $query = $query->select(['_id','title','create_time', 'update_time', 'content', 'describe','category_name'])
               ->from(['blog','blog_article'])
               ->where(['>', 'create_time', 0]);

        // 按标签名称搜素
        if (isset($params['title']) && !empty($params['title'])) {
            $query->andWhere(['like', 'title', $params['title']]);
        }

        // 返回分页列表信息
        $result = (new Model())->getPageListOnQuery($params, $query);

        // 处理id
        foreach ($result['list'] as $key => $item) {
            $result['list'][$key]['_id'] = (string) $result['list'][$key]['_id'];
            $result['list'][$key]['content'] = \yii\helpers\HtmlPurifier::process($result['list'][$key]['content']);
        }
        return $result;
    }
}
