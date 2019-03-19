<?php

namespace app\models;

use Yii;

/**
 * This is the model class for collection "test_collection1".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 */
class TestCollection1 extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['blog', 'test_collection1'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'title',
            'by',
            'likes',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'by', 'likes'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
        ];
    }
}
