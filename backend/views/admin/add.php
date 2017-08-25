<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'status',['inline'=>1])->radioList([1=>'正常',0=>'禁用']);
echo $form->field($model,'email');
echo $form->field($assignModel,'roles',['inline'=>1])->checkboxList(\backend\models\AssignForm::getPermissionItems());
?>


<?php echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);?>
 <a class="btn btn-default" href="<?=\yii\helpers\Url::to(['admin/index']) ?>">返回</a>
<?php $form=\yii\bootstrap\ActiveForm::end();?>
