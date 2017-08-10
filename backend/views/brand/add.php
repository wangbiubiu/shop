<?php
$form=\yii\bootstrap\ActiveForm::begin();
//添加品牌名
echo $form->field($model,'name');
//品牌描述
echo $form->field($model,'intro')->textarea();
//图片
echo $form->field($model,'logoImg')->fileInput();
//回显
if($model->logo){
    echo \yii\bootstrap\Html::img($model->logo);
}
//排行
echo $form->field($model,'sort');
//单选
echo $form->field($model,'status')->radioList(['0'=>'隐藏','1'=>'显示']);
//提交
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);?>
<a class="btn btn-default" href="<?=\yii\helpers\Url::to(['brand/index']) ?>">返回</a>
<?php $form=\yii\bootstrap\ActiveForm::end();?>
