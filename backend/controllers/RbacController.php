<?php

namespace backend\controllers;

use backend\filters\AccessFilter;
use backend\models\Brand;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use Yii;
use yii\data\Pagination;
use yii\db\Query;

class RbacController extends \yii\web\Controller
{
    //权限列表
    public function actionPermissionIndex(){
        //获取所有权限
        $permissions = \Yii::$app->authManager->getPermissions();
//        var_dump($permissions);exit;
//        $query=new query;
//        $rows=$query->from('auth_item')->where('type=2')->select('*');
//        $page = new Pagination([
//                                   //            获取总条数
//                                   'totalCount' => $rows->count(),
//                                   'defaultPageSize' => 5,
//                               ]);
//        $rows = $rows->offset($page->offset)
//                     ->limit($page->pageSize)
//                     ->all();
//        var_dump($rows);exit;

        return $this->render('permission-index',['permissions'=>$permissions]);
    }
//    添加权限
    public function actionAddPermission(){
        $model = new PermissionForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->save()){
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['permission-index']);
            }
        }
        return $this->render('permission',['model'=>$model]);
    }
    // 修改权限
    public function actionEditPermission($name){

//        var_dump($permission);exit;
        $model = new PermissionForm;
        //        回显
        $authManager = \Yii::$app->authManager;
        $permission =  $authManager->getPermission($name);
        $model->name=$permission->name;
        $model->description=$permission->description;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->edit($name)){
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['permission-index']);
            }
        }
        return $this->render('permission',['model'=>$model]);
    }
//    删除权限
    public function actionDeletePermission($name){
        $authManager = \Yii::$app->authManager;
        $adminRole =  $authManager->getPermission($name);
        if($authManager->remove($adminRole)){
            echo TRUE;
        }else{
            \Yii::$app->session->setFlash('danger','删除失败');
            return $this->redirect(['permission-index']);
        }
    }
//    角色列表
    public function actionRoleIndex(){
        //获取所有角色
        $roles = \Yii::$app->authManager->getRoles();
        return $this->render('roles-index',['roles'=>$roles]);
    }
//    添加角色
    public function actionAddRole()
    {
        $model = new RoleForm();
        //var_dump($_POST);exit;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->save()){
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['role-index']);
            }
        }
        return $this->render('role',['model'=>$model]);
    }
    //    修改角色
    public function actionEditRole($name)
    {
        $model = new RoleForm();
//        回显
        $authManager = \Yii::$app->authManager;
        $role =  $authManager->getRole($name);
        $model->name=$role->name;
        $model->description=$role->description;

        $permission =  $authManager->getPermissionsByRole($name);
//        var_dump($permission);exit;
        $permissiondata=array_keys($permission);
//        var_dump($permissiondata);exit;
        $model->permissions=$permissiondata;
//        先清除

        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->edit($name)){
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['role-index']);
            }
        }
        return $this->render('role',['model'=>$model]);
    }
//    删除角色
    public function actionDeleteRole($name){
        $authManager = \Yii::$app->authManager;
        $adminRole =  $authManager->getRole($name);
        if($authManager->remove($adminRole)){
            echo TRUE;
        }else{
            \Yii::$app->session->setFlash('danger','删除失败');
            return $this->redirect(['role-index']);
        }
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