<?php

namespace backend\controllers;

use backend\filters\AccessFilter;
use backend\models\Menu;
use Yii;

class MenuController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $rows=Menu::find()->all();
        return $this->render('index',['rows'=>$rows]);
    }
    public function actionAdd(){
        $model=new Menu();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['menu/index']);
            }
        }
        $data_url=\Yii::$app->authManager->getPermissions();
        $menu_data=Menu::find()->all();
        return $this->render('add',['model'=>$model,'data_url'=>$data_url,'menu_data'=>$menu_data]);
    }
    public function actionEdit($id){
        $model=Menu::findOne($id);
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['menu/index']);
            }
        }
        $data_url=\Yii::$app->authManager->getPermissions();
        $menu_data=Menu::find()->all();
        return $this->render('add',['model'=>$model,'data_url'=>$data_url,'menu_data'=>$menu_data]);
    }
    public function actionDelete($id){
        $model=Menu::findOne($id);
        $model->delete();
        echo TRUE;
    }
    public function actionTest(){
        $model=new Menu();
        var_dump($model->getMenuItems());
    }
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>AccessFilter::className(),
                'except'=>['login','logout','code','upload','welcome','s-upload'],//排除不需要权限验证的操作
            ]
        ];
    }

}