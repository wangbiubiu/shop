<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170820_032128_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey()->unsigned()->comment('自增主键'),
            'label'=>$this->string(50)->notNull()->comment('菜单名称'),
            'parent_id'=>$this->integer(11)->notNull()->comment('上级菜单'),
            'url'=>$this->string(50)->comment('路由'),
            'sort'=>$this->integer()->notNull()->comment('排序'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
