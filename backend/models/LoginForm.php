<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/17
 * Time: 15:28
 */

namespace backend\models;


use yii\base\Model;

class LoginForm extends Model{
    public $username;
    public $password;
    public $code;
    public $rememberMe;//记住我

    public function rules()
    {
        return [
            [['username','password'],'required'],
            ['code','captcha','captchaAction' => 'admin/code'],
            ['rememberMe','safe']//这个字段是安全的，不需要验证
        ];
        //return parent::rules(); // TODO: Change the autogenerated stub
    }
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'code' => '验证码',
            'rememberMe' => '保存密码',
        ];
    }
}