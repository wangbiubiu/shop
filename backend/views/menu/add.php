<?php
use dosamigos\ckeditor\CKEditor;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/11
 * Time: 22:19
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'label');
//echo $form->field($model, 'parent_id')->dropDownList(\yii\helpers\ArrayHelper::map($menu_data,'parent_id', 'label'),['prompt'=>'=上级菜单=']);
echo $form -> field($model,'parent_id')->dropDownList(yii\helpers\ArrayHelper::map(\backend\models\Menu::getMenu(),'id','label'),['prompt'=>'=请选择上级菜单=']);
echo $form->field($model, 'url')->dropDownList(\yii\helpers\ArrayHelper::map($data_url,'name', 'name'),['prompt'=>'=请选择路由=']);
echo $form->field($model,'sort');
?>
<!--//echo $form->field($modelconc,'content')->textarea();-->

<?php echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);?>
 <a class="btn btn-default" href="<?=\yii\helpers\Url::to(['menu/index']) ?>">返回</a>
<?php $form=\yii\bootstrap\ActiveForm::end();?>
