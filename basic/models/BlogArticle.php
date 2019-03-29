<?php

namespace app\models;

use Yii;

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
class BlogArticle extends \yii\mongodb\ActiveRecord
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'content', 'title', 'describe', 'create_time', 'update_time'], 'safe']
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
}
