<?php
?>
<?php ?>
    <a class="btn btn-default" href="<?=\yii\helpers\Url::to(['goods/add']) ?>">添加商品</a>
    <a class="btn btn-default" href="<?=\yii\helpers\Url::to(['goods/re']) ?>">回收站</a>
    <form id="w0" class="form-inline" action="/goods/index" method="get" role="form"><div class="form-group field-goodssearchform-name">

            <input type="text" id="goodssearchform-name" class="form-control" name="name" placeholder="商品名">


        </div><div class="form-group field-goodssearchform-sn ">

            <input type="text" id="goodssearchform-sn" class="form-control" name="sn" placeholder="货号" aria-invalid="false">

        </div><div class="form-group field-goodssearchform-minprice">

            <input type="text" id="goodssearchform-minprice" class="form-control" name="minPrice" placeholder="￥min">

        </div><div class="form-group field-goodssearchform-maxprice">
            <label class="sr-only" for="goodssearchform-maxprice">-</label>
            <input type="text" id="goodssearchform-maxprice" class="form-control" name="maxPrice" placeholder="￥max">

        </div><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span>搜索</button></form>
    <table class="table table-bordered table-responsive">
        <tr>
            <th>商品编号</th>
            <th>名称</th>
            <th>货号</th>
            <th>商品图标</th>
            <th>所属分类</th>
            <th>所属品牌</th>
            <th>市场价格</th>
            <th>商品价格</th>
            <th>库存</th>
            <th>上下架</th>
            <th>是否前台显示</th>
            <th>排行</th>
            <th width="160px">操作</th>
        </tr>
        <?php foreach($rows as $v): ?>
            <tr>
                <td><?= $v->id; ?></td>
                <td><?= $v->name; ?></td>
                <td><?= $v->sn; ?></td>
                <td><img width="50px" src="<?= $v->logo; ?>"></td>
                <td><?= $v->gcategory->name; ?></td>
                <td><?= $v->brand->name; ?></td>
                <td><?= $v->market_price; ?></td>
                <td><?= $v->shop_price; ?></td>
                <td><?= $v->stock; ?></td>
                <td><?php if($v->is_on_sale==1){
                        echo '是';
                    }else{echo '否';}; ?></td>
                <td><?php if($v->status==1){
                        echo '是';
                    }else{echo '否';}; ?></td>
                <td><?= $v->sort; ?></td>
                <td>
                    <a class="btn btn-default btn-xs" href="<?=\yii\helpers\Url::to(['goods/album','id'=>$v->id]);?>">查看相册</a>
                    <a class="btn btn-default btn-xs" href="<?=\yii\helpers\Url::to(['goods/content','id'=>$v->id]);?>">查看详情</a>
                    <?php if(\Yii::$app->user->can('goods/edit')){ ?>
                    <a class="btn btn-default btn-xs" href="<?=\yii\helpers\Url::to(['goods/edit','id'=>$v->id]);?>">编辑商品</a>
                    <?php } ?>
                    <?php if(\Yii::$app->user->can('goods/delete')){ ?>
                    <input class="btn btn-danger btn-xs" type="button" id="<?= $v->id?>" onclick="delmenu(<?=$v->id?>)"value="删除商品">
                    <?php } ?>
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