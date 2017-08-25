<?php
/* @var $this yii\web\View */
$form=\yii\bootstrap\ActiveForm::begin();
echo "<div class=row><div class=col-lg-4 col-md-9>";
echo $form->field($model,'beforePassword')->passwordInput();
echo "</div></div>";
echo "<div class=row><div class=col-lg-4 col-md-9>";
echo $form->field($model,'password')->passwordInput();
echo "</div></div>";
echo "<div class=row><div class=col-lg-4 col-md-9>";
echo $form->field($model,'password_confirm')->passwordInput();
echo "</div></div>";
?>
<?php echo \yii\bootstrap\Html::submitButton('确认修改',['class'=>'btn btn-info']);?>
<?php $form=\yii\bootstrap\ActiveForm::end();?>
