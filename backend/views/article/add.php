<?php
use dosamigos\ckeditor\CKEditor;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/11
 * Time: 22:19
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model, 'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($data,'id', 'name'));
echo $form->field($model,'sort');
echo $form->field($model,'status')->radioList(['0'=>'隐藏','1'=>'显示']);?>
<!--//echo $form->field($modelconc,'content')->textarea();-->
<?= $form->field($modelconc, 'content')->widget(CKEditor::className(), [
    'options' => ['rows' => 6],
    'preset' => 'basic'
]) ?>
<?php echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);?>
 <a class="btn btn-default" href="<?=\yii\helpers\Url::to(['article/index']) ?>">返回</a>
<?php $form=\yii\bootstrap\ActiveForm::end();?>
