<?php

namespace app\models;

use Yii;
use app\components\Errors;
/**
 * This is the model class for table "auth_item_child".
 *
 * @property string $parent
 * @property string $child
 *
 * @property AuthItem $parent0
 * @property AuthItem $child0
 */
class AuthItemChild extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_item_child';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent', 'child'], 'required'],
            [['parent', 'child'], 'string', 'max' => 64],
            [['parent', 'child'], 'unique', 'targetAttribute' => ['parent', 'child']],
            [['parent'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['parent' => 'name']],
            [['child'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['child' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'parent' => 'Parent',
            'child' => 'Child',
        ];
    }
    /**
     * 给指定角色赋权限
     * @param array $params 提交的角色与赋予的权限信息
     */
    public static function addAccessByRole($params)
    {
        // 角色节点不能为空
        if (!isset($params['role_name']) || empty($params['role_name'])) {
            return [false, 
                Errors::ERROR_CODE_PARAMES_INCORECT, 
                Errors::ERROR_MESSAGE_PARAMES_INCORECT
            ];
        }

        // 指定的权限节点不能为空
        if (!isset($params['access_list']) || empty($params['access_list'])) {
            return [false, 
                Errors::ERROR_CODE_PARAMES_INCORECT, 
                Errors::ERROR_MESSAGE_PARAMES_INCORECT
            ];
        }
        
        // 构造角色信息对象
        $parent = new \stdclass();
        $parent->name = $params['role_name'];
        
        // 删除原有的权限信息
        $deleteResult = Yii::$app->authManager->removeChildren($parent);

        //循环插入权限信息
        foreach ($params['access_list'] as $key => $value) {
            $child = new \stdclass();
            $child->name = $value;
            $result = Yii::$app->authManager->addChild($parent, $child);
            unset($child);
        }

        // 赋权限成功
        return [true, Errors::ERROR_CODE_OK, Errors::ERROR_MESSAGE_OK];
    }
}
