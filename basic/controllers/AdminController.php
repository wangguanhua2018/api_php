<?php
namespace app\controllers;

use Yii;
use app\components\CommonController;
use app\components\Errors;
use app\models\AdminUser;

class AdminController extends CommonController
{
    // 定义模型
    public $modelClass = 'app\models\AdminUser';
    /**
     * 后台用户登录
     */
    public function actionLogin()
    {
    	// 获取客户端输入的登录信息
    	$params = $this->params;
        
    	// 验证用户名以及密码
    	list ($result, $code, $message, $userInfo) = (new AdminUser())->login($params);
        if ($result === false) {
            return $this->generateResponseCheck($code, $message); 
        }
       
        // 生成用户登录token
        $user['token'] = $this->generateAuthToken($userInfo->id);
        
        // 登录成功
    	return $this->generateResponseCheck($code, $message, $user);
    }
}





