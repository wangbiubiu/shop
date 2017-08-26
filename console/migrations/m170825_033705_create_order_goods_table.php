<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_goods`.
 */
class m170825_033705_create_order_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_goods', [
            'id' => $this->primaryKey(),
            'order_id'=>$this->integer()->notNull()->comment('订单id'),
            'goods_id'=>$this->integer()->notNull()->comment('商品id'),
            'goods_name'=>$this->string(50)->notNull()->comment('商品名称'),
            'logo'=>$this->string(255)->notNull()->comment('图片'),
            'price'=>$this->decimal(10,2)->notNull()->comment('价格'),
            'amount'=>$this->integer()->notNull()->comment('数量'),
            'total'=>$this->decimal(10,2)->notNull()->comment('小计'),
        ]);
    }
//id	primaryKey
//order_id	int	订单id
//goods_id	int	商品id
//goods_name	varchar(255)	商品名称
//logo	varchar(255)	图片
//price	decimal	价格
//amount	int	数量
//total	decimal	小计

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_goods');
    }
}
