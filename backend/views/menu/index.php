<?php
?>
<a class="btn btn-default" href="<?=\yii\helpers\Url::to(['menu/add']) ?>">添加菜单</a>
<table class="table table-bordered table-responsive">
    <tr>
        <th>菜单编号</th>
        <th>菜单名称</th>
        <th>菜单路由</th>
        <th>菜单排名</th>
        <?php if(\Yii::$app->user->can('menu/edit') and \Yii::$app->user->can('menu/deletes')){ ?>
        <th>操作</th>
        <?php } ?>
    </tr>
    <?php foreach($rows as $v): ?>
        <tr>
            <td><?= $v->id; ?></td>
            <td><?php if($v->parent_id!=0){ echo "一".$v->label;}else{
               echo $v->label;} ?></td>
            <td><?= $v->url; ?></td>
            <td><?= $v->sort; ?></td>
            <?php if(\Yii::$app->user->can('menu/edit') and \Yii::$app->user->can('menu/deletes')){ ?>
            <td>
                <?php if(\Yii::$app->user->can('menu/edit')){ ?>
                <a class="btn btn-default" href="<?=\yii\helpers\Url::to(['menu/edit','id'=>$v->id]);?>">编辑菜单</a>
                <?php } ?>
                <?php if(\Yii::$app->user->can('menu/deletes')){ ?>
                <input class="btn btn-danger" type="button" id="<?= $v->id?>" onclick="delmenu(<?=$v->id?>)"value="删除">
                <?php } ?>
            </td>
        <?php } ?>
        </tr>
    <?php endforeach; ?>
</table>
<script>
    function delmenu(id){
//弹窗提示是否删除
        var isdel = confirm("删除?");
//返回true表示删除
        if (isdel === true){
//利用Ajax请求根据id删除数据
            $.getJSON("http://admin.yiishop.com/menu/delete","id="+id+"",function (data){
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