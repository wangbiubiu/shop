<?php

namespace backend\models;
use creocoder\nestedsets\NestedSetsBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\HttpException;

/**
 * This is the model class for table "goods_category".
 *
 * @property string $id
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property integer $parent_id
 * @property string $intro
 */
class GoodsCategory extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                 'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }

    public static function tableName()
    {
        return 'goods_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','parent_id'],'required'],
            ['parent_id','validateParentId'],
            ['depth','validateDepth'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 50],
        ];
    }
    public function validateParentId(){
        if($this->id==$this->parent_id){
            $this->addError('parent_id','父节点不能为自身');
        }
    }
    public function validateDepth(){
        if($this->depth>=2){
//            echo 111;exit;
            throw new HttpException(403,'最多添加三级分类');
//            $this->addError('parent_id','最多添加三级分类');
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增主键',
            'tree' => '树id',
            'lft' => '左值',
            'rgt' => '右值',
            'depth' => '层级',
            'name' => '名称',
            'parent_id' => '请选择上级分类',
            'intro' => '简介',
        ];
    }
    public static function getZNodes()
    {
        return Json::encode(
            ArrayHelper::merge(
                [['id'=>0,'parent_id'=>0,'name'=>'顶级分类']],
                self::find()->select(['id','name','parent_id'])->asArray()->all()
            )
        );
    }
    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}
