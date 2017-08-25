<?php
/* @var $this yii\web\View */
$form=\yii\bootstrap\ActiveForm::begin();
echo "<div class=row><div class=col-lg-4 col-md-9>";
echo $form->field($model,'username');
echo "</div></div>";
echo "<div class=row><div class=col-lg-4 col-md-9>";
echo $form->field($model,'password')->passwordInput();
echo "</div></div>";
echo $form->field($model,'rememberMe')->checkbox([0]);
?>
<?= $form->field($model,'code')->widget(\yii\captcha\Captcha::className(), [
    'captchaAction'=>'admin/code',
    'template' => '<div class="row"><div class="col-lg-3 col-md-9">{input}</div><div class="col-lg-3 col-md-3">{image}</div> </div>'
]) ?>
<?php echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-info']);?>
<?php $form=\yii\bootstrap\ActiveForm::end();?>
