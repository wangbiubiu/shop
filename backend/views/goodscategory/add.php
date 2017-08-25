<?php
/**
 * @var $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
//name	varchar(50)	名称
echo $form->field($model,'name');
//parent_id	int()	上级分类id
echo $form->field($model,'parent_id')->hiddenInput();
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';
//intro	text()	简介
echo $form->field($model,'intro')->textarea();

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);?>
    <a class="btn btn-default" href="<?=\yii\helpers\Url::to(['goodscategory/index']) ?>">返回</a>
<?php
$form=\yii\bootstrap\ActiveForm::end();
if($model->getIsNewRecord()){
    $model->parent_id='null';
}

$zNodes = \backend\models\GoodsCategory::getZNodes();
//加载ztree的静态资源
//加载css文件
//$this->registerCssFile('@web/zTree/css/demo.css');
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
//加载js文件   //depends 依赖关系
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
//加载js代码
$this->registerJs(new \yii\web\JsExpression(
                      <<<JS
                   var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback:{
                onClick:function(event, treeId, treeNode){
                    console.log(treeNode.id);
                    //赋值给parent_id
                    $("#goodscategory-parent_id").val(treeNode.id);
                }
            }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
       var zNodes = {$zNodes};
 
       zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
       //展开所有节点
       zTreeObj.expandAll(true);
       //修改功能   根据当前分类的parent_id选中节点
        var node = zTreeObj.getNodeByParam("id", "{$model->parent_id}", null);//根据id获取节点
        zTreeObj.selectNode(node);
JS
                  ));
