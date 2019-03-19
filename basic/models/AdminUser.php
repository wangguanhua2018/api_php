<?php

namespace app\models;

use Yii;
use app\components\Errors;
use yii\db\Query;
use yii\rbac\Item;
/**
 * This is the model class for table "admin_user".
 *
 * @property int $id 用户id
 * @property string $username 用户名
 * @property string $password 密码
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class AdminUser extends Model
{
    public $role_name;
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';
    const SCENARIO_UPDATE = 'update';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'role_name'], 'required' , 'on' => static::SCENARIO_UPDATE],
            [['username', 'password'], 'required' , 'on' => static::SCENARIO_LOGIN],
            [['username', 'password', 'role_name'], 'required' , 'on' => static::SCENARIO_REGISTER],
            [['username'], 'unique', 'on' => [static::SCENARIO_UPDATE, static::SCENARIO_REGISTER]],
            [['create_time', 'update_time'], 'integer'],
            [['username'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
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
     * 新增管理员并且指定角色
     * @param object $model 管理员模型
     * @param array $params 客户端提交的管理员信息
     */
    public function editUser($model, $params)
    {   

        // 保存场景如果是编辑则保存编辑之前的密码
        $isNewRecord = $model->isNewRecord;
        if (!$isNewRecord && empty($params['password'])) {
            $oldPassword = $model->password;
        }
        
        // 模型赋值
        if (!$model->load($params, '') || !$model->validate()) {
            return [false, Errors::ERROR_CODE_SAVE_FAIL, $this->getModelError($model), null];
        }
    
        // 处理用户密码
        $model->password = isset($oldPassword) ? $oldPassword : Yii::$app->getSecurity()->generatePasswordHash($model->password);

        // 保存用户
        $model->save();
        
        // 获取角色信息
        $role = Yii::$app->authManager->getRole($params['role_name']);
        if (empty($role)) {
            return [false, Errors::ERROR_CODE_SAVE_FAIL, Errors::ERROR_MESSAGE_DATA_NOT_EXIST, null];
        }

        // 如果是编辑则先删除用户与角色的关系
        if (!$isNewRecord) {
            $result = Yii::$app->authManager->revokeAll($model->id);
        }
        
        // 新增用户与角色的关系
        $result = Yii::$app->authManager->assign($role, $model->id);
        
        // 新增管理员成功
        return [true, Errors::ERROR_CODE_OK,Errors::ERROR_MESSAGE_OK, $model->attributes];   
    }
    /**
     * 获取管理员列表
     * @param array $params 客户端查询参数信息
     */
    public function getUserList($params)
    {   
        // 获取查询条件
        $query = (new Query())->select('a.id,a.username,a.create_time,update_time,c.name,c.description')
            ->from(['a' => static::tableName(), 'b' => AuthAssignment::tableName(), 'c' => AuthItem::tableName()])
            ->where('{{a}}.[[id]]={{b}}.[[user_id]]')
            ->andwhere('{{b}}.[[item_name]]={{c}}.[[name]]')
            ->andWhere(['c.type' => Item::TYPE_ROLE]);
    
        // 按用户名搜素
        if (isset($params['username']) && !empty($params['username'])) {
            $query->andWhere(['like', 'a.username', $params['username']]);
        }
        
        // 返回分页列表信息
        return $this->getPageListOnQuery($params, $query);
    }
    /**
     * 删除管理员用户
     * @param array $params 客户端请求的参数信息
     */
    public static function delUser($model)
    {
        // 删除管理员用户
        $result = $model->delete();

        // 删除管理员用户角色关系
        $result = Yii::$app->authManager->revokeAll($model->id);

        return [true, Errors::ERROR_CODE_OK, Errors::ERROR_MESSAGE_OK]; 
    }
    /**
     * 验证用户名和密码以及生成用户登录token
     * @param array $params 客户端输入的用户信息
     */
    public function login($params)
    {   
        // 用户名以及密码必填
        $model = new static(['scenario' => static::SCENARIO_LOGIN]);
        if (!$model->load($params, '') || !$model->validate()) {
           return [false, 
               Errors::ERROR_CODE_SAVE_FAIL, 
               $this->getModelError($model),
               null
           ];
        }
       
        // 查找用户名
        $userInfo = static::find()->where(['username' => $params['username']])->one();
        if (empty($userInfo)) {
            return [false, 
                Errors::ERROR_CODE_USERINFO_WRONG, 
                Errors::ERROR_MESSAGE_USERINFO_WRONG,
                null
            ];
        }

        // 密码错误
        if (!Yii::$app->getSecurity()->validatePassword($params['password'], $userInfo->password)) {
            return [false, 
                Errors::ERROR_CODE_USERINFO_WRONG, 
                Errors::ERROR_MESSAGE_USERINFO_WRONG,
                null
            ];
        }
        
        return [true, 
            Errors::ERROR_CODE_OK, 
            Errors::ERROR_MESSAGE_OK,
            $userInfo
        ];
    }
}
