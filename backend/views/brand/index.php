<?php
?>
<a class="btn btn-default" href="<?=\yii\helpers\Url::to(['brand/add']) ?>">添加品牌</a>
<table class="table">
    <tr>
        <th>品牌编号</th>
        <th>品牌名称</th>
        <th>品牌LOGO</th>
        <th>品牌排名</th>
        <th>前台是否显示</th>
        <th>品牌简介</th>
        <th>操作</th>
    </tr>
    <?php foreach($rows as $v): ?>
        <tr>
            <td><?= $v->id; ?></td>
            <td><?= $v->name; ?></td>
            <td><img width="50px" src="<?= $v->logo; ?>"></td>
            <td><?= $v->sort; ?></td>
            <td><?php if($v->status==1){
                echo '是';
                }else{echo '否';}; ?></td>
            <td><?= $v->intro; ?></td>
            <td>
                <a class="btn btn-default" href="<?=\yii\helpers\Url::to(['brand/edit','id'=>$v->id]);?>">编辑品牌</a>
                <a class="btn btn-danger" href="<?=\yii\helpers\Url::to(['brand/delete','id'=>$v->id]);?>">删除品牌</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?= \yii\widgets\LinkPager::widget([
   'pagination' => $pager,		//控制器赋值的分页变量
   'maxButtonCount' => 6,		//每页最多显示按钮个数
//   'nextPageLabel' => '>>>',	//改变下一页按钮的字符，设置为fase不显示
//   'prevPageLabel' => '<<<',	//改变上一页按钮的字符，设置为fase不显示
   'firstPageLabel' => '首页',		//首页，默认不显示
   'lastPageLabel' => '尾页',		//尾页，默认不显示
   'hideOnSinglePage' => false,	//如果你的数据过少，不够2页，默认不显示分页，可以设置为false
   //'options' => ['class' => '样式']		//设置样式
])?>