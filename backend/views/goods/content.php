<a class="btn btn-default" href=<?=\yii\helpers\Url::to(['goods/index']) ?>>返回</a> <a class="btn btn-default" href=<?=\yii\helpers\Url::to(['goods/edit','id'=>$title->id]) ?>>修改</a><br>
<h1><?php
echo $title->name;
?></h1>
<hr>

<div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style="width: 500px;">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
        <div class="item active">
            <img src="<?=$title->logo;?>" alt="...">
        </div>
        <?php foreach($img as $v): ?>
        <div class="item">
            <img src="<?= $v->path; ?>" alt="...">
        </div>
        <?php endforeach; ?>

    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<hr>
<?php echo $content->content; ?>