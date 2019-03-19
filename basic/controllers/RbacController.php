<?php

namespace app\controllers;

use Yii;
use app\components\CommonController;
use app\components\AuthController;
use app\components\Errors;
use app\models\AuthItem;
use app\models\AdminUser;
use app\models\AuthItemChild;

class RbacController extends AuthController
{   
    // 定义模型
    public $modelClass = 'app\models\AuthItem';

    public function actions()
    {   
        $actions = parent::actions();
        unset($actions['delete'], $actions['create']);
        unset($actions['index']);
        unset($actions['update']);

        return $actions;
    }
    /**
     * 获取权限节点列表
     */
    public function actionPermission()
    {   
        // 获取客户端参数信息
        $params = $this->params;

        // 实例化模型
        $model  = new AuthItem();
        // 根据参数信息获取权限列表数据
        $result = $model->getPermissionList($params);

        return $this->generateResponseCheck(Errors::ERROR_CODE_OK, Errors::ERROR_MESSAGE_OK, $result);
    }
    /**
     * 新增权限节点
     */
    public function actionCreate()
    {   
        // 获取客户端参数信息
        $params = $this->params;
        // 实例化模型
        $model  = new AuthItem();
        // 存取权限数据
        list ($result, $code, $message) = $model->addPermission($params);
        
        // 返回存取结果
        return $this->generateResponseCheck($code, $message);
    }
    /**
     * 编辑权限节点
     */
    public function actionEdit()
    {   
        // 获取客户端参数
        $params = $this->params;
        // 实例化模型
        $model  = new AuthItem();
        // 修改权限信息
        list ($result, $code, $message) = $model->editPermission($params);

        return $this->generateResponseCheck($code, $message);
    }
    /**
     * 删除权限节点
     */
    public function actionDel()
    {
        // 获取客户端参数信息
        $params = $this->params;
        // 实例化模型
        $model  = new AuthItem();
        
        // 删除指定节点
        list ($result, $code, $message) = $model->delPermission($params);

        return $this->generateResponseCheck($code, $message);
    }
    /**
     * 新增管理员
     */
    public function actionCreateuser()
    {   
        // 获取客户端参数
        $params = $this->params;

        // 实例化模型
        $model = new AdminUser();
        $model->scenario = AdminUser::SCENARIO_REGISTER;

        // 新增管理员
        list ($result, $code, $message, $info) = $model->editUser($model, $params);

        return $this->generateResponseCheck($code, $message);
    }
     /**
     * 编辑管理员
     */
    public function actionUpdateuser()
    {   
        // 获取客户端参数
        $params = $this->params;
        if (!isset($params['admin_id']) || empty($params['admin_id'])) {
            return $this->generateResponseCheck(Errors::ERROR_CODE_PARAMES_INCORECT, Errors::ERROR_MESSAGE_PARAMES_INCORECT);
        }

        // 查找对应的模型数据
        $model = $this->findUserModel($params['admin_id']);
        $model->scenario = AdminUser::SCENARIO_UPDATE;
        
        // 编辑管理员信息
        list ($result, $code, $message, $info) = $model->editUser($model, $params);

        return $this->generateResponseCheck($code, $message);
    }
    /**
     * 获取管理员列表
     */
    public function actionUser()
    {   
        // 获取客户端参数信息
        $params = $this->params;

        // 实例化模型
        $model  = new AdminUser();
        // 根据参数信息获取权限列表数据
        $result = $model->getUserList($params);

        return $this->generateResponseCheck(Errors::ERROR_CODE_OK, Errors::ERROR_MESSAGE_OK, $result);
    }
    /**
     * 删除管理员
     */
    public function actionDeluser()
    {
        // 获取客户端参数
        $params = $this->params;
        if (!isset($params['admin_id']) || empty($params['admin_id'])) {
            return $this->generateResponseCheck(Errors::ERROR_CODE_PARAMES_INCORECT, Errors::ERROR_MESSAGE_PARAMES_INCORECT);
        }

        // 查找对应的模型数据
        $model = $this->findUserModel($params['admin_id']);
      
        // 编辑管理员信息
        list ($result, $code, $message) = AdminUser::delUser($model);

        return $this->generateResponseCheck($code, $message);
    }
    /**
     * 获取指定角色拥有的权限
     */
    public function actionNodes()
    {   
        // 获取客户端参数
        $params = $this->params;
        if (!isset($params['role_name']) || empty($params['role_name'])) {
            return $this->generateResponseCheck(Errors::ERROR_CODE_PARAMES_INCORECT, Errors::ERROR_MESSAGE_PARAMES_INCORECT);
        }

        // 获取指定角色所拥有的权限
        $nodes = array_keys(Yii::$app->authManager->getPermissionsByRole($params['role_name']));

        // 将节点数据统一成字符串类型
        $nodes = array_map(function($value){
            return (string)$value;
        }, $nodes);
        
        return $this->generateResponseCheck(Errors::ERROR_CODE_OK, Errors::ERROR_MESSAGE_OK, $nodes);
    }
    /**
     * 给指定角色赋权限
     */
    public function actionAccess() 
    {
        // 获取客户端参数
        $params = $this->params;

        // 给指定角色添加权限
        list ($result, $code, $message) = AuthItemChild::addAccessByRole($params);

        return $this->generateResponseCheck($code, $message);
    }
    /**
     * 根据管理员用户id查找管理员用户模型
     * @param string $userId 管理员用户id
     */
    protected function findUserModel($userId)
    {
        // 查找对应的模型数据
        $model = AdminUser::findOne($userId);
        if (empty($model)) {
            return $this->generateResponseCheck(Errors::ERROR_CODE_DATA_NOT_EXIST, Errors::ERROR_MESSAGE_DATA_NOT_EXIST);
        }

        return $model;
    }
}
