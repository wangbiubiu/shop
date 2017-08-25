<form action="<?=\yii\helpers\Url::to(['index/test']) ?>" method="post">
    <input type="hidden" name="_csrf-frontend" value="<?=Yii::$app->request->csrfToken?>" >
    <input type="date" name="date">
    <input type="submit" value="提交">
</form>