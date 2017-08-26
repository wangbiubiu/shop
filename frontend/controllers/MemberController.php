<?php

namespace frontend\controllers;


use app\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\helpers\Json;
use yii\helpers\Url;

class MemberController extends \yii\web\Controller
{
    public function actionRegist()
    {
//        exit;
        $model=new Member();
        if($model->load(\Yii::$app->request->post(),'')){
//            验证手机

            $tel=\Yii::$app->request->post()['tel'];
            $captcha= isset(\Yii::$app->request->post()['captcha'])?\Yii::$app->request->post()['captcha']:"错";
//            var_dump($captcha);exit;
            $redis = new \Redis();
            $redis->connect('127.0.0.1');
            if($redis->get('sms_'.$tel)==$captcha){
            //        var_dump($email);exit;
            //        if()
//            $url=Url::to(['member/regist']);
            $model->save(FALSE);
            $url=Url::to(['member/login']);
            echo "注册成功:<a href={$url}>登录</a>";
            exit;
            }else{
                $url=Url::to(['member/regist']);
                echo "手机验证出错:<a href={$url}>重新注册</a>";
                exit;
            }
        }
        return $this->render('regist');
    }
    public function actionLogin(){
//        exit;
        $model = new LoginForm();

        $request=\Yii::$app->request;
        if($model->load(\Yii::$app->request->post(),'')){
//            var_dump($model->rememberMe);exit;
            //2 接收数据
            if($model->validate()){
                //3 验证账号密码是否正确
                $admin = Member::findOne(['username'=>$model->username]);// status = 1
                if($admin){
                    //验证密码
                    if(\Yii::$app->security->validatePassword($model->password,$admin->password_hash)){
                        $admin->last_login_time = time();
                        $admin->last_login_ip   = ip2long( $request->getUserIP() );
                        $admin->save();
                        //登陆与自动登录
                        if($model->rememberMe=='on'){
                            \Yii::$app->user->login($admin,7*24*3600);//登陆状态保持7天
                        }else{
                        \Yii::$app->user->login($admin);
                        }
//                        登录成功把购物车的数据放入数据库清除cookie

                        $cookies = \Yii::$app->request->cookies;
                        //1.看cookie中是否有购物车
                        $carts = $cookies->getValue('carts');
                        $member_id=\Yii::$app->user->identity->id;
                        if(!$carts==null){
                            $carts = unserialize($carts);
                            foreach($carts as $goods_id=>$amount){
                                $res=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
//                                如果没有这个商品就新增一个
                                if($res==NULL){
                                    $model=new Cart();
                                    $model->goods_id=$goods_id;
                                    $model->member_id=$member_id;
                                    $model->amount=$amount;
                                    if($model->validate()){
                                        $model->save();
                                    }
//                                    否则修改数量
                                }else{
                                    $res->amount=$res->amount+$amount;
                                    $res->save();
                                }
                            }
//                            最后清除cookie
                            $resObj = \Yii::$app->response->cookies;
                            //删除也要使用response来操作
                            $resObj->remove('carts');
                        }
                        return $this->redirect(['index/index']);
                    }else{
                        //提示密码错误
                        echo '密码错误';exit;
                    }
                }else{
                    echo '用户名错误';exit;
                }
            }else{
                echo $model->getFirstError("code");
                exit;
            }
        }
        return $this->render('login',['model'=>$model]);
//        return $this->render('login');
    }
//    验证用户名是否已经存在
    public function actionValidateUsername($username)
    {
//        exit;
        $model = new Member();
        $model->username = $username;
        $model->validate('username');
        if($model->hasErrors('username')){
            //$model->getErrors();
            return Json::encode($model->getFirstError('username'));
        }
        return Json::encode(true);
    }
    //    验证用户名是否已经存在
    public function actionValidateEmail($email)
    {
        //        exit;
        $model = new Member();
        $model->email = $email;
        $model->validate('email');
        if($model->hasErrors('email')){
            //$model->getErrors();
            return Json::encode($model->getFirstError('email'));
        }
        return Json::encode(true);
    }
    public function actionValidateCheckcode($checkcode)
    {
        //        exit;
        $model = new Member();
        $model->checkcode = $checkcode;
        $model->validate('checkcode');
        if($model->hasErrors('checkcode')){
            //$model->getErrors();
            return Json::encode($model->getFirstError('checkcode'));
        }
        return Json::encode(true);
    }

//    public function actionValidateCode($code)
//    {
//        //        exit;
//        $model = new LoginForm();
//        $model->code = $code;
//        $model->validate('code');
//        if($model->hasErrors('code')){
//            //$model->getErrors();
//            return Json::encode($model->getFirstError('code'));
//        }
//        return Json::encode(true);
//    }
    public function actionCaptcha($tel){
        $code = rand(1000,9999);
        $result = \Yii::$app->sms->setParams(['smscode'=>$code])->setNumber($tel)->send();
//        var_dump($result);exit;
        if($result->Message=="OK"){
            $redis = new \Redis();
            $redis->connect('127.0.0.1');
            $redis->set('sms_'.$tel,$code);
        }
        echo TRUE;
    }

    public function actionLogout()
    {
        $userObj=\Yii::$app->user;
        //        清除
        $userObj->switchIdentity(null);
        //用户退出登陆操作
//        var_dump($userObj->logout());
//        Yii::$app->session->setFlash("success","退出成功");
        return $this->redirect(['index/index']);
    }

}