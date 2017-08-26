<?php
/* @var $this yii\web\View */
?>
    <a class="btn btn-default" href=<?= \yii\helpers\Url::to(['article/add'])?>>添加</a>
    <a class="btn btn-default" href=<?= \yii\helpers\Url::to(['article/re'])?>>回收站</a>

    <form id="" class="form-inline" action="/article/index" method="get" role="form"><div class="form-group field-articlesearchform-name">
            <label class="sr-only" for="articlesearchform-name">Name</label>
            <input type="text" id="articlesearchform-name" class="form-control" name="name" placeholder="查找标题">
        </div><div class="form-group field-articlesearchform-intro">
            <label class="sr-only" for="articlesearchform-intro">Intro</label>
            <input type="text" id="articlesearchform-intro" class="form-control" name="intro" placeholder="查找描述">
        </div><button type="submit" class="btn btn-default">搜索</button></form>
    <table class="table table-bordered table-responsive">
        <tr>
            <th>文章编号</th>
            <th>文章名称</th>
            <th>文章描述</th>
            <th>文章分类</th>
            <th>文章排名</th>
            <th>是否显示在前台</th>
            <th>创建时间</th>

            <th>操作</th>

        </tr>
        <?php foreach($rows as $v): ?>
            <tr>
                <td><?= $v->id; ?></td>
                <td><?= $v->name; ?></td>
                <td><?= $v->intro; ?></td>
                <td><?= $v->content->name; ?></td>
                <td><?= $v->sort; ?></td>
                <td><?= $v->status?'是':'否'; ?></td>
                <td><?= $v->create_time; ?></td>
                <td><a class="btn btn-default" href=<?= \yii\helpers\Url::to(['article/content','id'=>$v->id])?>>查看内容</a>
    <?php if(\Yii::$app->user->can('article/edit')){ ?>
        <a class="btn btn-default" href=<?= \yii\helpers\Url::to(['article/edit','id'=>$v->id])?>>修改</a>
        <?php } ?>
        <?php if(\Yii::$app->user->can('article/delete')){ ?>
            <input class="btn btn-danger" type="button" id="<?= $v->id?>" onclick="delmenu(<?=$v->id?>)"value="删除">
            <?php }?>
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
