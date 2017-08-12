<?php
use yii\web\JsExpression;
$form=\yii\bootstrap\ActiveForm::begin();
//添加品牌名
echo $form->field($model,'name');
//品牌描述
echo $form->field($model,'intro')->textarea();
//上传数据库
echo $form->field($model,'logo')->hiddenInput();
//上传表单
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
                                                   'url' => yii\helpers\Url::to(['s-upload']),
                                                   'id' => 'test',
                                                   'csrf' => true,
                                                   'renderTag' => false,
                                                   'jsOptions' => [
                                                       'formData'=>['someKey' => 'someValue'],
                                                       'width' => 80,
                                                       'height' => 30,
                                                       'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
                                                       ),
                                                       'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        $('#img').attr("src",data.fileUrl);
        $("#brand-logo").val(data.fileUrl);
    }
}
EOF
                                                       ),
                                                   ]
                                               ]);



//回显
    echo \yii\bootstrap\Html::img($model->logo,['id'=>'img']);
//排行
echo $form->field($model,'sort');
//单选
echo $form->field($model,'status')->radioList(['0'=>'隐藏','1'=>'显示']);
//提交
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);?>
 <a class="btn btn-default" href="<?=\yii\helpers\Url::to(['brand/index']) ?>">返回</a>
<?php $form=\yii\bootstrap\ActiveForm::end();?>
