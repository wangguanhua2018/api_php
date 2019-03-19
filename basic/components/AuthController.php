<?php
namespace app\components;

use Yii;
use app\components\Errors;

class AuthController extends CommonController
{   
    // 保存参与token加密的信息
    protected $payload = [];
    // 定义超级管理员的角色节点名称
    protected $superAdmin = ['super_wangguanhua'];
    /**
     * 需要登录的接口请求,验证token是否正确
     * 验证token的准确性之后判断当前角色的权限
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {    
        if (!parent::beforeAction($action)) {
            return false;
        }
        
        // 验证token信息
        $validateTokenResult = $this->validateUserToken();
        if ($validateTokenResult !== true) {
            Yii::$app->response->data = $validateTokenResult;
            return false;
        }
        
        // 验证当前用户是否有当前的操作权限
        $validateAccessResult = $this->validateUserAccess($action);
        if ($validateAccessResult !== true) {
            Yii::$app->response->data = $validateAccessResult;
            return false;
        }

        return true;
    }
    /**
     * 验证用户的token信息
     * {@inheritdoc}
     */
    protected function validateUserToken()
    {
        // 获取请求的头部信息
        $headers = Yii::$app->request->headers;
        // 获取token
        $token = $headers->get('User-Token');
        
        // token缺失
        if (!$headers->has('User-Token') || empty($token)) {
            return $this->generateResponseCheck(Errors::ERROR_CODE_TOKEN_MISS, Errors::ERROR_MESSAGE_TOKEN_MISS);
        }

        try 
        {   
            $this->payload = JWT::decode($token, $this->userTokenKey, $this->allowedAlgs);
            return true;
        } catch (\Exception $e) {
            
            // 获取token错误提示
            $message = $e->getMessage();
            return $this->generateResponseCheck(Errors::ERROR_CODE_TOKEN_WRONG, $message);
        }    
    }
    /**
     * 验证是否有操作权限
     * {@inheritdoc}
     */
    protected function validateUserAccess($action)
    {
        // 如果是超级管理则直接通过
        $payload = $this->payload;
        $role = current(array_keys(Yii::$app->authManager->getRolesByUser($payload->user_id)));
        if (in_array($role, $this->superAdmin)) {
            return true;
        }
        
        // 获取当前的权限节点信息
        $module = $action->controller->module->id;
        $permissionName = $action->controller->id . "/" . $action->id;
        if (!empty($module)) {
            $permissionName = $module . "/" . $permissionName;
        }
         
        // 非超级管理员则根据设置的权限进行判断
        $checkAccessResult = Yii::$app->authManager->checkAccess($payload->user_id, $permissionName);
        if ($checkAccessResult) {
            return true;
        }

        // 当前用户没有权限操作当前权限节点
        return $this->generateResponseCheck(Errors::ERROR_CODE_NO_ACCESS, Errors::ERROR_MESSAGE_NO_ACCESS); 
    }

}





