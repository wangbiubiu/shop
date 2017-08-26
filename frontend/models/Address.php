<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $name
 * @property string $cmbProvince
 * @property string $cmbCity
 * @property string $cmbArea
 * @property string $address
 * @property integer $tel
 * @property integer $status
 * * @property integer $member_id
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id','name','status', 'cmbProvince', 'cmbCity', 'cmbArea', 'address', 'tel'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'cmbProvince' => 'Cmb Province',
            'cmbCity' => 'Cmb City',
            'cmbArea' => 'Cmb Area',
            'address' => 'Address',
            'tel' => 'Tel',
            'status' => 'Status',
        ];
    }
}
