<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_day_count`.
 */
class m170812_144448_create_goods_day_count_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_day_count', [
            'day'=>$this->date()->comment('日期'),
            'count'=>$this->integer(11)->notNull()->comment('商品数')
        ]);
        $this->addPrimaryKey('day','goods_day_count','day');
    }
//day	date	日期
//count	int	商品数

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_day_count');
    }
}
