<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\components\Jwt;
use app\components\CommonController;
use app\components\AuthController;
use app\models\TestCollection1;
use yii\rbac\DbManager;
use app\models\Rbac;

class SiteController extends CommonController
{   
    public $modelClass = 'app\models\User';
   
    /**
     * {@inheritdoc}
     */
    public function actions()
    {   
        $actions = parent::actions();
        unset($actions['delete'], $actions['create']);
        unset($actions['index']);
        //$actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    } 
    public function prepareDataProvider()
    {   
        return 123;
    }
    public function actionCreate()
    {   
        //$a = Yii::$app->request->post();
        //$d = $a['k'];
        //var_dump($a);
        return $d;
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {  
        ///test git gitgitttttttttt
       // var_dump(\Yii::$app->db->getDriverName());die();
        //$a = (new Rbac())->test();
        //$a = \Yii::$app->authManager->getPermissionsByRole('test');
       //$a = new DbManager checkAccess($userId, $permissionName, $params = [])
        //$a = \Yii::$app->authManager->checkAccess(1, 'site/login');
       //return $this->generateResponseCheck(0, 'success', '123');
       //$collection = Yii::$app->mongodb->getCollection('test_collection1');
       //$infos = $collection->find(['by' => '菜鸟教程']);
       //$a = current($infos);
       //$result = [];
       /*
       foreach($infos as $val){
            $result[] = $val['_id']->id;
        }*/
        //current(array)
       //$a = current($result);
       //$c = $a['_id']->{'$oid'};
       //$d = $c->{'$id'};
       //$d = $c['oid'];
       //$a = $infos->_id;
       //$d = '123';
        //ini_set(”memory_limit”,”80M”);
        //$result = TestCollection1::find()->limit(10)->all();
        //var_dump($result);die();
        $data = [
            'title' => 'wanggh',
            'url' => 'www.baidu.com',
            'likes' => 800,
            'by' => 'twgh456'
        ];
        $model = new TestCollection1();
        $model->load($data, '');
        $result = $model->save();
        var_dump($result);
        die();
        /*
        $t1 = microtime(true);
         for ($i=1; $i  < 150000; $i++) {
             $data['likes'] = $i;
             $model = new TestCollection1();
             $model->load($data, '');
             $result = $model->save();
             unset($model);
         }
        $t2 = microtime(true);
        echo '耗时'.round($t2-$t1,3).'秒<br>';die();
        $model = new TestCollection1();
        $model->load($data, '');
        $result = $model->save();
       */
       // $t1 = microtime(true);
        $result = TestCollection1::find()->where(['<', 'likes', 100000])->orderBy('likes DESC')->limit(10)->all();
        //$t2 = microtime(true);
        //echo '耗时'.round($t2-$t1,3).'秒<br>';//die();

        foreach ($result as $key => $item) {
            $data[$key] = $item->toArray();
        }
        //$result = TestCollection1::find()->where(['<', 'likes', 100000])->orderBy('likes DESC')->limit(2)->all()->toArray();
        $a = current($data);
        var_dump($a['_id']);die();
        //$b = current(current($model->getErrors()));
        //$a = TestCollection1::find()->one()->toArray();
        //$b = $a['_id'];
        //$c = $a['_id']->id;
        //return $this->generateResponseCheck(0, 'success', null);
        //*/
        /*
        return "aaaa";die();
        var_dump(Yii::$app->request->get());die();
        echo json_encode(['a' => 12,'b' => 452]);die();
        try {
           $key = "example_key";
           $decoded = Jwt::decode('asdsadadasdads', $key, array('HS256'));
 
print_r($decoded);
            
        } catch (\Exception $e) {
            $error = $e->getMessage();
            var_dump($error);
        }
  /*

            $key = "example_key";
$token = array(
    "iss" => "http://example.org", // #非必须。issuer 请求实体，可以是发起请求的用户的信息，也可是jwt的签发者。
    "aud" => "http://example.com", //#非必须。接收该JWT的一方。
    "iat" => 1356999524, //#非必须。issued at。 token创建时间，unix时间戳格式
    "nbf" => 1357000000 // # 非必须。not before。如果当前时间在nbf里的时间之前，则Token不被接受；一般都会留一些余地，比如几分钟。
);

 
     $jwt = Jwt::encode($token, $key);
      
     $decoded = Jwt::decode('asdsadadasdads', $key, array('HS256'));
 
print_r($decoded);
     //var_dump($jwt);
        //return $this->render('index');
        //var_dump(Yii::warning('test0123'));
        
        //Yii::error('test test');
        //$a = (new ContactForm())->test();
        //echo 123;die();*/
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {   
        var_dump($this->payload);die();
        //var_dump(Yii::$app->request->post());die();
        $token = array(
            "iss" => "http://example.org", // #非必须。issuer 请求实体，可以是发起请求的用户的信息，也可是jwt的签发者。
            "aud" => "http://example.com", //#非必须。接收该JWT的一方。
            "iat" => time(), //#非必须。issued at。 token创建时间，unix时间戳格式
            //"nbf" => 1357000000 // # 非必须。not before。如果当前时间在nbf里的时间之前，则Token不被接受；一般都会留一些余地，比如几分钟。
            "exp" => time() + 30
        );

        echo $data['token'] = $this->generateAuthToken(4);
        die();
        $a = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9leGFtcGxlLm9yZyIsImF1ZCI6Imh0dHA6XC9cL2V4YW1wbGUuY29tIiwiaWF0IjoxNTUxNDIyMjI1LCJleHAiOjE1NTE0MjIyNTV9.OJcALMdQZ1Xyzxj5S-Vim4ZEv0YrTYfvxzSfgp_o7Pg";
        //$result = Jwt::decode($a, 'example_key', array('HS256'));
        //var_dump($result);die();
        //return $this->generateResponseCheck(0, '成功', $data);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
