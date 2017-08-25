<?php

namespace frontend\controllers;

use frontend\models\Address;

class AddressController extends \yii\web\Controller
{

    public function actionIndex()
    {
        $model=new Address();
//        var_dump(\Yii::$app->request->post());
//        var_dump($model->load(\Yii::$app->request->post(),''));exit;
        if($model->load(\Yii::$app->request->post(),'') && $model->validate()){
//            var_dump(\Yii::$app->request->post());exit;
            $model->save();
            return $this->redirect(['index']);
        }
        $datas=Address::find()->all();
        return $this->render('address',['datas'=>$datas]);
    }
    public function actionStatus($id){
        $model=Address::findOne($id);
        $model->status=1;
        $model->save();
        return $this->redirect(['index']);
    }
    public function actionEdit($id){
        $model=Address::findOne(['id'=>$id]);
        //        var_dump(\Yii::$app->request->post());
        //        var_dump($model->load(\Yii::$app->request->post(),''));exit;
        if($model->load(\Yii::$app->request->post(),'') && $model->validate()){
            //            var_dump(\Yii::$app->request->post());exit;
            $model->save();
            return $this->redirect(['index']);
        }
        $datas=Address::find()->all();
        return $this->render('address',['datas'=>$datas,'model'=>$model]);
    }
    public function actionDelete($id){
        $model=Address::findOne(['id'=>$id]);
        $model->delete();
        echo TRUE;
    }
}
