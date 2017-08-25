<?php

namespace backend\controllers;

use backend\filters\AccessFilter;
use backend\models\Admin;
use backend\models\AssignForm;
use backend\models\LoginForm;
use backend\models\ModifyForm;
use frontend\models\Login;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;

class AdminController extends \yii\web\Controller
{
    public function actions()
    {
        return [
            'code' => [
                'class' => 'yii\captcha\CaptchaAction',
                'minLength' => 2,
                'maxLength' => 3
            ]
        ];
    }
    //    public function behaviors()
    //    {
    //        return [
    //            //基于存取的权限控制器主要是要配置此过滤器
    //            'access' => [
    //                'class' => AccessControl::className(),
    //                'rules' => [
    //                    [
    //                        'allow' => 'true',     //允许请求
    //                        'actions' => ['logout','index','add','edit','code'],        //允许请求的方法
    //                        'roles' => ['@']        //允许以登陆状态请求
    //                    ],
    //                    [
    //                        'allow' => 'true',     //允许请求
    //                        'actions' => [ 'login','code','logout'],        //允许请求的方法
    //                        'roles' => ['?']//允许以非登陆状态请求
    //                    ]
    //                ]
    //            ]
    //        ];
    //    }
    public function actionLogin(){
        $model = new LoginForm();
        $request=\Yii::$app->request;
        if($model->load(\Yii::$app->request->post())){
            //2 接收数据
            if($model->validate()){
                //3 验证账号密码是否正确
                $admin = Admin::findOne(['username'=>$model->username]);// status = 1
                if($admin){
                    //验证密码
                    if(\Yii::$app->security->validatePassword($model->password,$admin->password_hash)){
                        $admin->last_login_time = time();
                        $admin->last_login_ip   = ip2long( $request->getUserIP() );
                        $admin->save();
                        //登陆与自动登录
                        if($model->rememberMe==1){
                        \Yii::$app->user->login($admin,7*24*3600);//登陆状态保持7天
                        }else{
                            \Yii::$app->user->login($admin);
                        }
                        return $this->redirect(['welcome']);
                    }else{
                        //提示密码错误
                        $model->addError('password','密码错误');
                    }
                }else{
                    $model->addError('username','账号不存在');
                }
            }
        }
        return $this->render('login',['model'=>$model]);
    }
    public function actionIndex(){
        $rows=Admin::find();
        //        $rows=Ar::find()->where("article.status>-1 and article.name like '%$name%' and article.intro like '%$intro%'");
        $page = new Pagination([
                                   //            获取总条数
                                   'totalCount' => $rows->count(),
                                   'defaultPageSize' => 10,
                               ]);
        $rows = $rows->offset($page->offset)
                     ->limit($page->pageSize)
                     ->all();
        //        分配模型用于显示搜索

        //2 将数据赋值给视图
        //3 选择视图显示数据
        return $this->render('index', ['rows' => $rows, 'pager' => $page]);
    }
    public function actionAdd()
    {
        $request=\Yii::$app->request;
        $model=new Admin();
        $assignModel=new AssignForm();
        if($request->isPost){
//            绑定
            $model->load($request->post());
            $assignModel->load($request->post());
            if($model->validate() and $assignModel->validate()){
//                exit;
//                保存现在模型中处理一下
                $model->save();
                $user_id=$model->id;
//                var_dump($user_id);exit;
                $assignModel->save($user_id);
                Yii::$app->session->setFlash("success","添加成功");
                return $this->redirect(['admin/index']);
            }
        }
        return $this->render('add',['model'=>$model,'assignModel'=>$assignModel]);
    }

    public function actionEdit($id)
    {
        $request=\Yii::$app->request;
        $model=Admin::findOne(['id'=>$id]);
        $assignModel=new AssignForm();
        $authManager = \Yii::$app->authManager;
        $role=array_keys($authManager->getRolesByUser($id));
//        var_dump($role);exit;
        $assignModel->roles=$role;
        if($request->isPost){
            //            绑定
            $model->load($request->post());
            $assignModel->load($request->post());
            if($model->validate() and $assignModel->validate()){
                //                保存现在模型中处理一下
                $model->save();
                $user_id=$model->id;
                //                var_dump($user_id);exit;
                $assignModel->edit($user_id);
                Yii::$app->session->setFlash("success","添加成功");
                return $this->redirect(['admin/index']);
            }else{
                Yii::$app->session->setFlash("danger",$model->getErrors());
                return $this->redirect(['admin/add']);
            }
        }
        return $this->render('add',['model'=>$model,'assignModel'=>$assignModel]);
    }
    public function actionLogout()
    {
        $userObj=\Yii::$app->user;
//        清除
        $userObj->switchIdentity(null);
        //用户退出登陆操作
        $userObj->logout();
        Yii::$app->session->setFlash("success","退出成功");
        return $this->redirect(['admin/login']);
    }
    public function actionDelete($id){
        $obj=Admin::findOne($id);
        $obj->delete();
        echo TRUE;
    }
//    修改自己密码
    public function actionModify(){
        $model=new ModifyForm();
//        获取当前登录信息

//        获取修改模型 用于修改 和验证
        $userModel=\Yii::$app->user->identity;
        $request=\Yii::$app->request;
//        var_dump($sessionOBJ->email);
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
//                验证传过来的密码和旧密码是否相同
                if(\Yii::$app->security->validatePassword($model->beforePassword,$userModel->password_hash)){
                    $userModel->password_hash=Yii::$app->security->generatePasswordHash($model->password);
                    $userModel->save();
                    $userObj=\Yii::$app->user;
                    //        清除
                    $userObj->switchIdentity(null);
                    Yii::$app->session->setFlash("success","修改成功请重新登录");
                    return $this->redirect(['admin/login']);
                }else{
                    $model->addError('beforePassword','旧密码错误');
                }
            }
        }
        return $this->render('modify',['model'=>$model]);
    }

    public function actionWelcome(){
        return $this->render('welcome');
//        $model=new Admin();
//        $data=$model->getMenuItems();
//        var_dump($data);exit;
    }
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>AccessFilter::className(),
                'except'=>['login','logout','code','upload','welcome','s-upload'],//排除不需要权限验证的操作
            ]
        ];
    }

}