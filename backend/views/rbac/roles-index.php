<a class="btn btn-default" href="<?= \yii\helpers\Url::to(['rbac/add-role']) ?>">添加角色</a>
<table id="table_id_example" class="display">
    <thead>
    <tr>
        <th>名称</th>
        <th>描述</th>
        <?php if(\Yii::$app->user->can('rbac/edit-role') and \Yii::$app->user->can('rbac/delete-role')){ ?>
            <th>操作</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($roles as $role):?>
    <tr>
        <td><?=$role->name?></td>
        <td><?=$role->description?></td>
        <?php if(\Yii::$app->user->can('rbac/edit-role') and \Yii::$app->user->can('rbac/delete-role')){ ?>
        <td>
            <?php if(\Yii::$app->user->can('rbac/edit-role')){ ?>
            <a class="btn btn-default" href=<?= \yii\helpers\Url::to(['rbac/edit-role','name'=>$role->name])?>>修改</a>
            <?php } ?>
            <?php if(\Yii::$app->user->can('rbac/delete-role')){ ?>
            <input class="btn btn-danger" type="button" id="<?= $role->name?>" onclick="delmenu('<?=$role->name?>')"value="删除">
            <?php } ?>
        </td>
        <?php } ?>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>

<?php
$this->registerCssFile('@web/datatable/media/css/jquery.dataTables.css');
$this->registerJsFile('@web/datatable/media/js/jquery.dataTables.js',
                      ['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs(<<<JS
$(document).ready( function () {
    $('#table_id_example').DataTable({
            language: {
        "sProcessing": "处理中...",
        "sLengthMenu": "显示 _MENU_ 项结果",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
        "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "表中数据为空",
        "sLoadingRecords": "载入中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首页",
            "sPrevious": "上页",
            "sNext": "下页",
            "sLast": "末页"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
    }
    });
} );
JS
);
?>
<script>
    function delmenu(id){
//弹窗提示是否删除
        var isdel = confirm("删除?");
//返回true表示删除
        if (isdel === true){
//利用Ajax请求根据id删除数据
            $.getJSON("http://admin.yiishop.com/rbac/delete-role","name="+id+"",function (data){
//判定数据库是否删除成功成功返回1
                if (data === 1){
//根据id获取对应的父节点并删除
//console.log($("#"+id+"").parent().parent());
                    $("#"+id+"").parent().parent().remove();
                }
            })
        }
    }
</script>
