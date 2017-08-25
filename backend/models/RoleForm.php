<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/18
 * Time: 15:47
 */

namespace backend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permissions;//权限
    public function rules(){
     return [
         [['name','description'],'required'],
         ['permissions','safe'],
     ];
    }
    public function attributeLabels(){
        return[
            'name'=>'角色名称',
            'description'=>'角色描述',
            'permissions'=>'分配权限'
        ];
    }
    public static function getPermissionItems()
    {
        return ArrayHelper::map(\Yii::$app->authManager->getPermissions(),'name','description');
    }
    public function save(){
        $authManager = \Yii::$app->authManager;
        if($authManager->getRole($this->name)){
            $this->addError('name','角色已存在');
            return false;
        }else{
//            var_dump($this->permissions);exit;
            $role = $authManager->createRole($this->name);
            $role->description = $this->description;
            $authManager->add($role);
            //角色关联权限
//            var_dump($this->permissions);exit;
            if(is_array($this->permissions)){
                foreach ($this->permissions as $permissionName){
                    $permission = $authManager->getPermission($permissionName);
                    $authManager->addChild($role,$permission);//角色，权限
                }
            }
            return true;
        }
    }
    public function edit($name){
        $authManager = \Yii::$app->authManager;
        if($this->name==$name){//名字不改
            $role              = $authManager->getRole( $name );
            $role->description = $this->description;
            $authManager->update( $name, $role );
            //        修改权限前先清除权限
            $authManager->removeChildren($role);
            if( is_array( $this->permissions ) ){
                foreach( $this->permissions as $permissionName ){
                    $permission = $authManager->getPermission( $permissionName );
                    $authManager->addChild( $role, $permission );//角色，权限
                }
            }
            return true;
        }else{
            if( $authManager->getRole( $this->name ) ){
                $this->addError( 'name', '角色已存在' );
                return FALSE;
            }else{
                $authManager       = \Yii::$app->authManager;
                $role              = $authManager->getRole( $name );
                $role->name        = $this->name;
                $role->description = $this->description;
                //        修改权限前先清除权限
                $authManager->update( $name, $role );
                $authManager->removeChildren($role);
                if( is_array( $this->permissions ) ){
                    foreach( $this->permissions as $permissionName ){
                        $permission = $authManager->getPermission( $permissionName );
                        $authManager->addChild( $role, $permission );//角色，权限
                    }
                }
            }
            return TRUE;
        }
    }
}