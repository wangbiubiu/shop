<?php

namespace frontend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $email
 * @property string $tel
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_login_time
 * @property integer $last_login_ip
 */
class Member extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $rememberMe;
    public $checkcode;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'tel', 'created_at', 'updated_at'], 'safe'],
            ['checkcode','captcha'],
//            [['status', 'created_at', 'updated_at', 'last_login_time', 'last_login_ip'], 'integer'],
//            [['username', 'password_hash', 'email', 'tel'], 'string', 'max' => 255],
//            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
//            [['tel'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'email' => 'Email',
            'tel' => 'Tel',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_login_time' => 'Last Login Time',
            'last_login_ip' => 'Last Login Ip',
        ];
    }
    public function beforeSave($insert)
    {
        //区分添加还是修改
        if($insert){
            $this->created_at = time();
            //设置auth_key
            $this->auth_key = Yii::$app->security->generateRandomString();
            if($this->password_hash){
                $this->password_hash = Yii::$app->security->generatePasswordHash($this->password_hash);
            }
        }else{//修改
            $this->updated_at = time();
        }
        return parent::beforeSave($insert);
    }
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

}
