<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170821_105550_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->integer()->notNull(),
            'name'=>$this->string(50)->notNull(),
            'cmbProvince'=>$this->string(50)->notNull(),
            'cmbCity'=>$this->string(50)->notNull(),
            'cmbArea'=>$this->string(50)->notNull(),
            'address'=>$this->string(50)->notNull(),
            'tel'=>$this->integer(50)->notNull(),
            'status'=>$this->smallInteger(2)->defaultValue(0),
        ]);
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
