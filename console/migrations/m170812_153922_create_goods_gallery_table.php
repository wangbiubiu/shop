<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_gallery`.
 */
class m170812_153922_create_goods_gallery_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_gallery', [
            'id' => $this->primaryKey(11)->unsigned()->comment('自增主键'),
            'goods_id'=>$this->integer()->notNull()->comment('对应商品'),
            'path'=>$this->string(255)->notNull()->comment('图片'),
        ]);
    }
//id	primaryKey
//goods_id	int	商品id
//path	varchar(255)	图片地址

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_gallery');
    }
}