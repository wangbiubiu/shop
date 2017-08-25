<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/18
 * Time: 14:17
 */

namespace backend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;

class AssignForm extends Model{
    public $roles;
    public function rules(){
     return [
       [['roles'],'required'],
     ];
    }
    public function attributeLabels(){
        return[
            'roles'=>'角色分配',
        ];
    }
    public static function getPermissionItems()
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(),'name','description');
    }
    public function save($user_id){
        $authManager = \Yii::$app->authManager;
        if(is_array($this->roles)){
//            var_dump($this->roles);exit;
            foreach ($this->roles as $roleName){
                $role = $authManager->getRole($roleName);
                $authManager->assign($role,$user_id);//用户，角色
            }
        }
    }
    public function edit($user_id){
        $authManager = \Yii::$app->authManager;
        if(is_array($this->roles)){
            $authManager->revokeAll($user_id);
            //            var_dump($this->roles);exit;
            foreach ($this->roles as $roleName){
                $role = $authManager->getRole($roleName);
                $authManager->assign($role,$user_id);//用户，角色
            }
        }
    }
//    public function save(){
//        $authManager = \Yii::$app->authManager;
//        if( $authManager->getPermission( $this->name ) ){
//            $this->addError( 'name', '权限已存在' );
//            return FALSE;
//        }else{
//            $permission              = $authManager->createPermission( $this->name );
//            $permission->description = $this->description;
//            $authManager->add( $permission );
//            return TRUE;
//        }
//    }
//    //        修改权限
//    public function edit($name){
//        $authManager = \Yii::$app->authManager;
//        if($this->name==$name){//名字不改
//
//            $permission =  $authManager->getPermission($name);
//            $permission->description=$this->description;
//            $authManager->update($name,$permission);
//            return TRUE;
////            echo 111;exit;
//        }else{
//            if($authManager->getPermission($this->name)){
//                $this->addError('name','权限已存在');
//                return false;
//            }else{
//                $authManager             = \Yii::$app->authManager;
//                $permission              = $authManager->getPermission( $name );
//                $permission->name        = $this->name;
//                $permission->description = $this->description;
//                $authManager->update( $name, $permission );
//
//                return TRUE;
//            }
////            echo 222;exit;
//        }
//
//            /*$permission =  $authManager->getPermission('admin/add');
//$permission->name = 'user/add';
//$permission->description  = '添加用户';
//$authManager->update('admin/add',$permission);*/
//        }
}