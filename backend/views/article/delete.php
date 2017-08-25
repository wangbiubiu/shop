<?php
/* @var $this yii\web\View */
?>
    <a class="btn btn-default" href=<?= \yii\helpers\Url::to(['article/index'])?>>返回</a>
    <table class="table">
        <tr>
            <th>文章编号</th>
            <th>文章名称</th>
            <th>文章描述</th>
            <th>文章分类</th>
            <th>文章排名</th>
            <th>是否显示在前台</th>
            <th>创建时间</th>
            <?php if(\Yii::$app->user->can('article/deletes') and \Yii::$app->user->can('article/res')){ ?>
                <th>操作</th>
            <?php }?>
        </tr>
        <?php foreach($rows as $v): ?>
            <tr>
                <td><?= $v['id']; ?></td>
                <td><?= $v['name']; ?></td>
                <td><?= $v['intro']; ?></td>
                <td><?= $v['c_name']; ?></td>
                <td><?= $v['sort']; ?></td>
                <td><?php if(['status']==1){echo '是';}else{ echo '否';} ?></td>
                <td><?= $v['create_time']; ?></td>
                <?php if(\Yii::$app->user->can('article/deletes') and \Yii::$app->user->can('article/res')){ ?>
                <td>

                    <?php if(\Yii::$app->user->can('article/res')){ ?>
                    <a class="btn btn-default" href=<?= \yii\helpers\Url::to(['article/res','id'=>$v['id']])?>>还原</a>
                    <?php } ?>
                    <?php if(\Yii::$app->user->can('article/deletes')){ ?>
                    <input class="btn btn-danger" type="button" id="<?= $v['id']?>" onclick="delmenu(<?=$v['id']?>)"value="彻底删除"></td>
                    <?php } ?>
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
    function delmenu(id) {
//弹窗提示是否删除
        var isdel = confirm("删除?");
//返回true表示删除
        if (isdel === true){
//利用Ajax请求根据id删除数据
            $.getJSON("http://admin.yiishop.com/article/deletes","id="+id+"",function (data){
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
