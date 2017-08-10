<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;

class ArticlecategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $rows = ArticleCategory::find()->where('status>-1');
        $page = new Pagination([
                                   //            获取总条数
                                   'totalCount' => $rows->count(),
                                   'defaultPageSize' => 10,
                               ]);
        $rows = $rows->offset($page->offset)
                     ->limit($page->pageSize)
                     ->all();
        //2 将数据赋值给视图
        //3 选择视图显示数据
        return $this->render('index', ['rows' => $rows, 'pager' => $page]);
    }
//    添加文章分类
    public function actionAdd(){
//        创建模型
        $model=new ArticleCategory();
//        创建request
        $request=\YII::$app->request;
        if($request->isPost){
            $data=$request->post();
//            var_dump($data);exit;
//            绑定
            $model->load($data);
//            验证
            if($model->validate()){
                $model->save();
                //            成功跳转
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['articlecategory/index']);
            }
//            失败跳转
            \Yii::$app->session->setFlash('danger',$model->getErrors());
            return $this->redirect(['articlecategory/add']);
        }
//        显示添加页面
        return $this->render('add',['model'=>$model]);
    }
//    修改
    public function actionEdit($id){
        //        创建模型
        $model=ArticleCategory::findOne(['id'=>$id]);
        //        创建request
        $request=\YII::$app->request;
        if($request->isPost){
            $data=$request->post();
            //            var_dump($data);exit;
            //            绑定
            $model->load($data);
            //            验证
            if($model->validate()){
                $model->save();
                //            成功跳转
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['articlecategory/index']);
            }
            //            失败跳转
            \Yii::$app->session->setFlash('danger',$model->getErrors());
            return $this->redirect(['articlecategory/add']);
        }
        //        显示添加页面
        return $this->render('add',['model'=>$model]);
    }
//    删除
    public function actionDelete($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
//        改变状态
        $model->status=-1;
//        成功
        if($model->validate()){
            $model->save();
            //            成功跳转
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect(['articlecategory/index']);
        }
//        失败跳转
        \Yii::$app->session->setFlash('danger',$model->getErrors());
        return $this->redirect(['articlecategory/index']);
    }
}
