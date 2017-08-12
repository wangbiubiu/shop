<?php

namespace backend\controllers;

use backend\models\Ar;
use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\helpers\Url;

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
            return $this->redirect(['articlecategory/index']);
        }
        //        显示添加页面
        return $this->render('add',['model'=>$model]);
    }
//    删除
    public function actionDelete($id){
//        判断没有子类才能删
        $modelarticle= Ar::findOne(['article_category_id'=>$id]);
//        var_dump($modelarticle);exit;
        if($modelarticle==NULL){
        $model=ArticleCategory::findOne(['id'=>$id]);
//        改变状态
        $model->status=-1;
//        成功
        if($model->validate()){
            $model->save();
            //            成功跳转
            $url=Url::to(['articlecategory/re']);
            \Yii::$app->session->setFlash('success', "删除成功:<a href='$url'>查看回收站</a>");
            return $this->redirect(['articlecategory/index']);
        }
        }
//        失败跳转
        \Yii::$app->session->setFlash('danger','请先删除属于该类的文章(包括回收站)');
        return $this->redirect(['articlecategory/index']);
    }
//    回收站
    public function actionRe()
    {
        $rows = ArticleCategory::find()->where('status=-1');
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
        return $this->render('del', ['rows' => $rows, 'pager' => $page]);
    }
    //    完全删除
    public function actionDeletes($id){
        $res=ArticleCategory::deleteAll("id=$id");
        if($res){
            \Yii::$app->session->setFlash( 'success', '删除成功' );
            return $this->redirect(['articlecategory/re']);
        }
        \Yii::$app->session->setFlash( 'DANGER', '删除失败' );
        return $this->redirect(['articlecategory/re']);
    }
    //    还原
    public function actionRes($id){
        $model=ArticleCategory::findOne($id);
        $model->status=0;
        if($model->validate()){
            $model->save();
            $url=\yii\helpers\Url::to(['articlecategory/index']);
            \Yii::$app->session->setFlash( 'success', "数据已还原:<a href='$url'>查看</a>" );
            return $this->redirect(['articlecategory/re']);
        }
        \Yii::$app->session->setFlash( 'DANGER', '还原失败' );
        return $this->redirect(['articlecategory/re']);
    }

}
