

<?php
use yii\web\JsExpression;

$form=\yii\bootstrap\ActiveForm::begin();

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
//        回显
       
//          数据
        $("#box1").append("<input type=hidden value="+data.fileUrl+">");
//        使用ajax添加导入数据库
        $.getJSON("ajax","goods_id=$data->id&path="+data.fileUrl+"",function (data) {
                $("#box2").empty();
                $.each(data,function (i,v) {
                $("#box2").append("<img class=del src="+v.path+">");
                $("#box2").append("<a class='btn btn-danger' onclick=del("+v.id+") href='javascript:void(0);' type=button value=删除>删除</a><br>");
            });
        });
    }
}
EOF
                                                       ),
                                                   ]
                                               ]);
?>
<label>图片</label>
    <div id="box2">
        <?php
        foreach($model as $v):{
            echo "<img src='$v->path' class='del'><a  onclick='del($v->id)' href='javascript:void(0);' class='btn btn-danger' type=button value=删除>删除</a><br>";
        }
        endforeach;
        ?>
    </div>
<?php
//echo \yii\bootstrap\Html::img($model->path,['id'=>'img']);
//隐藏
//echo $form->field($model,'path')->hiddenInput();

$form=\yii\bootstrap\ActiveForm::end();
?>

<script>
    function del(id) {
//        console.dir(id);
        $.getJSON("del","id="+id+"&goods_id=<?= $data->id ?>",function (data) {
            $("#box2").empty();
            $.each(data,function (i,v) {
                $("#box2").append("<img class=del src="+v.path+">");
                $("#box2").append("<a class='btn btn-danger' onclick=del("+v.id+") href='javascript:void(0);' type=button value=删除>删除</a><br>");
            });
        });
    }
</script>
<a class="btn btn-default" href="<?=\yii\helpers\Url::to(['goods/index']) ?>">返回</a>