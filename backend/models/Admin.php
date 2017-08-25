<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_login_time
 * @property integer $last_login_ip
 */
class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $password;
    public $verifyCode;
    public $password_confirm;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            [ [ 'username', 'email', 'status' ], 'required' ],
            [ [ 'status', 'created_at', 'updated_at', 'last_login_time', 'last_login_ip' ], 'integer' ],
            [ [ 'username', 'password_hash', 'password_reset_token', 'email' ], 'string', 'max' => 255 ],
            [ [ 'password' ], 'string' ],
            [ [ 'auth_key' ], 'string', 'max' => 32 ],
            [ [ 'username' ], 'unique'],
            [ [ 'email' ], 'unique'],
            [ [ 'email' ], 'email' ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => '验证码',
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => '记住密码',
            'password' => '密码',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_login_time' => 'Last Login Time',
            'last_login_ip' => 'Last Login Ip',
            'password_confirm'=>'确认密码'
        ];
    }
    //保存之前要执行的代码  beforeSave()  save()    afterSave();  $insert是否是insert添加
    public function beforeSave($insert)
    {
        //区分添加还是修改
        if($insert){
            $this->created_at = time();
            //设置auth_key
            $this->auth_key = Yii::$app->security->generateRandomString();
        }else{//修改
            $this->updated_at = time();
        }
        if($this->password){
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        }
        return parent::beforeSave($insert);
    }
    /**
     * @param int|string $id
     * @return static
     * 根据id主键获取用户的实例对象
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
        // TODO: Implement findIdentity() method.
    }

    /**
     * @param mixed $token
     * @param null $type
     * 获取token登陆时的用户实例
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * @return mixed
     * 获取确定对象的id
     */
    public function getId()
    {
        return $this->id;
        // TODO: Implement getId() method.
    }

    /**
     * @return mixed
     * 获取自动登陆authkey
     */
    public function getAuthKey()
    {
        return $this->auth_key;
        // TODO: Implement getAuthKey() method.
    }
    /**
     * @param string $authKey
     * @return bool
     * 验证自动登陆authkey
     */
    public function validateAuthKey($authKey)
    {
        return $authKey === $this->getAuthKey();
        // TODO: Implement validateAuthKey() method.
    }
    public function getMenuItems(){
        $menuItems = [];
        //二级菜单演示
        //1 . 获取所有一级菜单
        $menus = Menu::findAll(['parent_id'=>0]);
        //2 遍历一级菜单
        foreach ($menus as $menu){
//                        var_dump($menu['id']);exit;
            //3.获取该一级菜单的所有子菜单
            $children = Menu::findAll(['parent_id'=>$menu['id']]);

            $items = [];
            //4遍历所有子菜单
            foreach ($children as $child){
                //根据用户权限决定是否添加到items里面
                if(Yii::$app->user->can($child->url)){
                    $items[] = ['label' =>$child->label, 'url' => [$child->url]];
                }
            }
//                        var_dump($items);exit;
//            如果有才显示
            if(!$items==[]){
                $menuItems[] = ['label'=>$menu->label,'items'=>$items];
            }
        }
        return $menuItems;
    }
}