<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "goods_gallery".
 *
 * @property string $id
 * @property integer $goods_id
 * @property string $path
 */
class GoodsGallery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['goods_id', 'path'], 'required'],
            [['goods_id'], 'integer'],
            [['path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增主键',
            'goods_id' => '对应商品',
            'path' => '图片',
        ];
    }
    //获取商品分类ztree数据
    public static function getZNodes()
    {
        return Json::encode(
            ArrayHelper::merge(
                [['id'=>0,'parent_id'=>0,'name'=>'顶级分类']],
                self::find()->select(['id','name','parent_id'])->asArray()->all()
            )
        );
    }
}
