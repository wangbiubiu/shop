<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property string $id
 * @property string $label
 * @property integer $parent_id
 * @property string $url
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'parent_id', 'sort'], 'required'],
            [['parent_id', 'sort'], 'integer'],
            [['label', 'url'], 'string', 'max' => 50],
            ['parent_id','validateParentId'],
        ];
    }


    public function validateParentId(){
        if(0==$this->parent_id and $this->url){
            $this->addError('url','顶级菜单不能添加路由');
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增主键',
            'label' => '菜单名称',
            'parent_id' => '上级菜单',
            'url' => '路由',
            'sort' => '排序',
        ];
    }
    public static function getMenu()
    {
        return
            //合并数组
            ArrayHelper::merge(
            //顶级菜单
                [['id'=>0,'parent_id'=>0,'label'=>'顶级菜单']],
                self::find()->select(['id','label','parent_id'])->where('parent_id=0')->asArray()->all()
            );
    }
}
