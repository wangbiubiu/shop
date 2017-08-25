<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'description');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);?>
<a class="btn btn-default" href="<?=\yii\helpers\Url::to(['rbac/permission-index']) ?>">返回</a>
<?php \yii\bootstrap\ActiveForm::end();?>