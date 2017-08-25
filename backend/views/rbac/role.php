<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'description');
echo $form->field($model,'permissions',['inline'=>1])->checkboxList(\backend\models\RoleForm::getPermissionItems());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);?>
<a class="btn btn-default" href="<?=\yii\helpers\Url::to(['rbac/role-index']) ?>">返回</a>
<?php \yii\bootstrap\ActiveForm::end();?>


