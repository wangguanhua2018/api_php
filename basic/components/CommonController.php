<?php
namespace app\components;

use yii\rest\ActiveController;
use yii\web\Response;

class CommonController extends ActiveController
{   
	// 禁用自带的csrf验证
    public $enableCsrfValidation = false;
    // 定义一个属性存取客户端参数信息
    protected $params;

    // 定义用于生成权限toekn的key
    protected $userTokenKey = "example_key";
    // 定义默认的token加密算法
    protected $allowedAlgs  = ['HS256'];
    // 定义token的有效时间
    protected $tokenExpTime = 3600;
    // 定义参与加密生成token的信息
    protected $payloadDefault = [
        'iss' => 'http://test.vueserver.com',
        'aud' => 'http://localhost:8081',
    ];
    /*
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        return $behaviors;
    }*/
    /**
     * 需要登录的接口请求,验证token是否正确
     */
    public function beforeAction($action)
    {   
        if (!parent::beforeAction($action)) {
            return false;
        }

        // 获取以及保存客户端请求参数
        $params = $this->saveParams();

        return true;
    }
    /**
     * 保存客户端提交过来的参数信息
     */
    protected function saveParams()
    { 
        $request = \Yii::$app->request;
        $params = $request->isGet ? $request->get() : $request->bodyParams;

        // 保存前端提交过来的参数
        $this->params = $params;

        return $params;
    }
    /**
     * 生成token信息
     * @param array $token 用于生成token的信息
     * @param string $key 用于生成token的key
     */
    protected function generateAuthToken($user_id, $key = null)
    {   
    	// 使用默认的key
        if (empty($key)) {
            $key = $this->userTokenKey;
        }
        
        // 定义参与加密生成token的信息
        $time = time();
        $payload = array_merge($this->payloadDefault, [
            'user_id' => $user_id,
            'iat' => $time,
            'exp' => $time + $this->tokenExpTime
        ]);

        // 根据提供的信息生成jwt机制的token字符串信息
        $jwtSting = JWT::encode($payload, $key);

        // 返回token字符串信息
        return $jwtSting;
    }
    /**
     * 统一输出格式
     * @param int $code 响应code
     * @param string $message 响应说明
     * @param unknow $data 响应数据
     */
    public static function generateResponseCheck($code, $message, $data = null, $version = '1.0') 
    {   
        // 统一返回数据响应格式
        return [
        	'code' => $code,
        	'message' => $message,
        	'data' => $data,
        	'version' => $version
        ]; 
    }
    
  

}





