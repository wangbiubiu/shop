<a class="btn btn-default" href=<?=\yii\helpers\Url::to(['article/index']) ?>>返回</a>
<?php if(\Yii::$app->user->can('article/delete')){ ?>
<a class="btn btn-default" href=<?=\yii\helpers\Url::to(['article/edit','id'=>$title->id]) ?>>修改</a>
    <?php } ?>
    <br>
<h1><?php
echo $title->name;
?></h1>
<hr>
<?php echo $content->content; ?>