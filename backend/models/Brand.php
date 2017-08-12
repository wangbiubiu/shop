<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property string $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{
//    保存图片
    public $logoImg;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'intro', 'logo','sort'], 'required'],
            [['intro'], 'string'],
            [['sort', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
//            [['logoImg','file',]],
            ['logoImg','file','extensions' => ['png', 'jpg', 'gif'],'skipOnEmpty'=>true],//skipOnEmpty 字段为空跳过当前验证 FALSE为必须上传
//            ['logoImg', 'file', 'extensions' => ['png', 'jpg', 'gif']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增主键',
            'name' => '名称',
            'intro' => '简介',
            'logoImg' => 'LOGO图片',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
}
