<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_detail`.
 */
class m170810_113649_create_article_detail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_detail', [
            'id' => $this->primaryKey(11)->unsigned()->comment('自增主键'),
            'content'=>$this->text()->notNull()->comment('文章内容'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_detail');
    }
}
