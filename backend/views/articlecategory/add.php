<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/10
 * Time: 18:45
 */
$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'name');
//描述
echo $form->field($model,'intro')->textarea();
//排行
echo $form->field($model,'sort');
//单选
echo $form->field($model,'status')->radioList(['0'=>'隐藏','1'=>'显示']);
//提交
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);?>
<a class="btn btn-default" href="<?=\yii\helpers\Url::to(['articlecategory/index']) ?>">返回</a>
<?php $from=\yii\bootstrap\ActiveForm::end(); ?>