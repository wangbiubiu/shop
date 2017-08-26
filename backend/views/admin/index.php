<?php
/* @var $this yii\web\View */
?>
    <a class="btn btn-default" href=<?= \yii\helpers\Url::to(['admin/add'])?>>添加</a>
<table class="table table-bordered table-responsive">
        <tr>
            <th>用户ID</th>
            <th>用户名</th>
            <th>密码</th>
            <th>最后登录时间</th>
            <th>最后登录IP</th>
            <?php if(\Yii::$app->user->can('admin/edit') and \Yii::$app->user->can('admin/delete')){ ?>
            <th>操作</th>
            <?php }?>
        </tr>
        <?php foreach($rows as $v): ?>
            <tr>
                <td><?= $v->id; ?></td>
                <td><?= $v->username; ?></td>
                <td><?= $v->email; ?></td>
                <td><?php if($v->last_login_time!=0){echo date("Y-m-d H:i:s",$v->last_login_time);}else{echo '无记录';}; ?></td>
                <td><?= long2ip($v->last_login_ip); ?></td>
                <?php if(\Yii::$app->user->can('admin/edit') and \Yii::$app->user->can('admin/delete')){ ?>
                <td>
                    <?php if(\Yii::$app->user->can('admin/edit')){ ?>
                    <a class="btn btn-default" href=<?= \yii\helpers\Url::to(['admin/edit','id'=>$v->id])?>>修改</a>
                    <?php } ?>
                    <?php if(\Yii::$app->user->can('admin/delete')){ ?>
                    <input class="btn btn-danger" type="button" id="<?= $v->id?>" onclick="delmenu(<?=$v->id?>)"value="删除">
                    <?php } ?>
                </td>
                <?php } ?>
            </tr>
        <?php endforeach;?>
    </table>
<?= \yii\widgets\LinkPager::widget([
                                       'pagination' => $pager,		//控制器赋值的分页变量
                                       'maxButtonCount' => 5,		//每页最多显示按钮个数
                                       //   'nextPageLabel' => '>>>',	//改变下一页按钮的字符，设置为fase不显示
                                       //   'prevPageLabel' => '<<<',	//改变上一页按钮的字符，设置为fase不显示
                                       'firstPageLabel' => '首页',		//首页，默认不显示
                                       'lastPageLabel' => '尾页',		//尾页，默认不显示
//                                       'hideOnSinglePage' => false,	//如果你的数据过少，不够2页，默认不显示分页，可以设置为false
                                       //'options' => ['class' => '样式']		//设置样式
                                   ])?>
<script>
    function delmenu(id){
//弹窗提示是否删除
        var isdel = confirm("删除?");
//返回true表示删除
        if (isdel === true){
//利用Ajax请求根据id删除数据
            $.getJSON("delete","id="+id+"",function (data){
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
