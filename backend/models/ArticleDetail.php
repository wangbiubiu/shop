<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article_detail".
 *
 * @property string $id
 * @property string $content
 */
class ArticleDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'content' => '文章内容',
        ];
    }
}
