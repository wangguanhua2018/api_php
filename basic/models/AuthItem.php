<?php

namespace app\models;

use Yii;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\rbac\DbManager;
use yii\rbac\Item;
use yii\db\Query;
use app\components\Errors;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property int $type
 * @property string $description
 * @property string $rule_name
 * @property resource $data
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property AuthItem[] $children
 * @property AuthItem[] $parents
 */
class AuthItem extends Model
{   
    public $oldName;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type', 'description'], 'required', 'on' => 'create'],
            [['name', 'type', 'oldName', 'description'], 'required', 'on' => 'update'],
            [['name'], 'required', 'on' => 'delete'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique', 'on' => 'create'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'type' => 'Type',
            'description' => 'Description',
            'rule_name' => 'Rule Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    /**
     * 获取节点列表
     * @param array $params 客户端查询参数信息
     */
    public function getPermissionList($params)
    {   
        // 获取查询条件
        $query = (new Query())->from(static::tableName())->where(['>', 'created_at', 0]);

        // 按描述搜索
        if (isset($params['description']) && !empty($params['description'])) {
            $query->andWhere(['like', 'description', $params['description']]);
            $query->orWhere(['like', 'name', $params['description']]);
        }
        
        // 按类型搜索
        if (isset($params['type']) && !empty($params['type'])) {
            $query->andWhere('type=:type', [':type' => $params['type']]);
        }

        // 获取分页信息列表
        $result = $this->getPageListOnQuery($params, $query);
        
        return $result;
    }
    /**
     * 新增节点
     * @param array $params 客户端提交的节点信息
     */
    public function addPermission($params) 
    {   
        // 实例化模型
        $model = new self(['scenario' => 'create']);
        
        //模型赋值
        $model->load($params, '');

        // 验证数据
        if (!$model->validate()) {
            return [false, Errors::ERROR_CODE_SAVE_FAIL, $this->getModelError($model)]; 
        }

        // 存取数据
        $permission = new Permission();
        $permission->name = trim($model->name);
        $permission->type = $model->type;
        $permission->description = trim($model->description);
        $saveResult = Yii::$app->authManager->add($permission);

        // 存取权限成功
        return [true, Errors::ERROR_CODE_OK, Errors::ERROR_MESSAGE_OK];
    }
    /**
     * 编辑节点
     * @param array $params 客户端提交的节点信息
     */
    public function editPermission($params)
    {
        // 实例化模型
        $model = new self(['scenario' => 'update']);
        
        //模型赋值
        $model->load($params, '');

        // 验证数据
        if (!$model->validate()) {
            return [false, Errors::ERROR_CODE_SAVE_FAIL, $this->getModelError($model)]; 
        }

        // 存取数据
        $permission = new Permission();
        $permission->name = trim($model->name);
        $permission->type = $model->type;
        $permission->description = trim($model->description);
        $saveResult = Yii::$app->authManager->update($params['oldName'], $permission);

        // 存取权限成功
        return [true, Errors::ERROR_CODE_OK, Errors::ERROR_MESSAGE_OK];
    }
    /**
     * 删除节点
     */
    public function delPermission($params)
    {
        // 实例化模型
        $model = new self(['scenario' => 'delete']);
        
        //数据验证
        if (!$model->load($params, '') || !$model->validate()) {
            return [false, Errors::ERROR_CODE_SAVE_FAIL, $this->getModelError($model)]; 
        }
        
        // 删除角色
        if ($model->type == Item::TYPE_ROLE) {
            $node = Yii::$app->authManager->getRole($model->name);
        }
        // 删除节点
        if ($model->type == Item::TYPE_PERMISSION) {
            $node = Yii::$app->authManager->getPermission($model->name);
        }
        
    
        Yii::$app->authManager->remove($node);
        return [true, Errors::ERROR_CODE_OK, Errors::ERROR_MESSAGE_OK];
    }
}
