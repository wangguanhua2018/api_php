<?php

namespace app\models;

use Yii;
use yii\db\Query;
use yii\rbac\Item;
/**
 * This is the model class for table "auth_assignment".
 *
 * @property string $item_name
 * @property string $user_id
 * @property int $created_at
 *
 * @property AuthItem $itemName
 */
class AuthAssignment extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_assignment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id'], 'required'],
            [['created_at'], 'integer'],
            [['item_name', 'user_id'], 'string', 'max' => 64],
            [['item_name', 'user_id'], 'unique', 'targetAttribute' => ['item_name', 'user_id']],
            [['item_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['item_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_name' => 'Item Name',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }
    /**
     * 根据用户id获取用户对应的角色信息
     */
    public static function getrolesInfoByUsers($usersId)
    {
        if (empty($usersId)) {
            return [];
        }

        $query = (new Query())->select('b.*,a.user_id')
            ->from(['a' => static::tableName(), 'b' => AuthItem::tableName()])
            ->where('{{a}}.[[item_name]]={{b}}.[[name]]')
            ->andWhere(['in', 'a.user_id', $usersId])
            ->andWhere(['b.type' => Item::TYPE_ROLE]);
       
        foreach ($query->all() as $row) {
            $roles[$row['user_id']]['role_name'] = $row['name'];
            $roles[$row['user_id']]['role_description'] = $row['description'];
        }

        return $roles;
    }
}
