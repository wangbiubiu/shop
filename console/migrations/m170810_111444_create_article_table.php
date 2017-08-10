<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m170810_111444_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(11)->unsigned()->comment('自增主键'),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->text()->notNull()->comment('简介'),
            'article_category_id'=>$this->integer(11)->notNull()->comment('文章分类id'),
            'sort'=>$this->integer()->notNull()->comment('排序'),
            'status'=>$this->smallInteger(2)->comment('状态'),
            'create_time'=>$this->timestamp()->comment('创建时间'),
        ]);
//        id	primaryKey
//name	varchar(50)	名称
//intro	text	简介
//article_category_id	int()	文章分类id
//sort	int(11)	排序
//status	int(2)	状态(-1删除 0隐藏 1正常)
//create_time	int(11)	创建时间
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
