<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/18
 * Time: 14:17
 */

namespace backend\models;


use yii\base\Model;

class PermissionForm extends Model{
    public $name;
    public $description;
    public function rules(){
     return [
       [['name','description'],'required'],
     ];
    }
    public function attributeLabels(){
        return[
            'name'=>'权限路由',
            'description'=>'权限描述'
        ];

    }

    public function save(){
        $authManager = \Yii::$app->authManager;
        if( $authManager->getPermission( $this->name ) ){
            $this->addError( 'name', '权限已存在' );

            return FALSE;
        }else{
            $permission              = $authManager->createPermission( $this->name );
            $permission->description = $this->description;
            $authManager->add( $permission );
            return TRUE;
        }
    }
    //        修改权限
    public function edit($name){
        $authManager = \Yii::$app->authManager;
        $permission =  $authManager->getPermission($name);
        if($this->name==$name){//名字不改
            $permission->description=$this->description;
            $authManager->update($name,$permission);
            return TRUE;
//            echo 111;exit;
        }else{
            if($authManager->getPermission($this->name)){
                $this->addError('name','权限已存在');
                return false;
            }else{
                $permission->name        = $this->name;
                $permission->description = $this->description;
                $authManager->update( $name, $permission);
                return TRUE;
            }
//            echo 222;exit;
        }

            /*$permission =  $authManager->getPermission('admin/add');
$permission->name = 'user/add';
$permission->description  = '添加用户';
$authManager->update('admin/add',$permission);*/
        }
}