<?php

namespace backend\controllers;

use backend\filters\AccessFilter;
use backend\models\Goods;
use backend\models\GoodsCategory;
use yii\base\Exception;
use yii\data\Pagination;
use yii\helpers\Url;

class GoodscategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $models=GoodsCategory::find();
        $page = new Pagination([
                                   //            获取总条数
                                   'totalCount' => $models->count(),
                                   'defaultPageSize' => 10,
                               ]);
        $rows = $models->offset($page->offset)
                     ->limit($page->pageSize)
                     ->orderBy('tree,lft')
                     ->all();
        //2 将数据赋值给视图
        //3 选择视图显示数据
        return $this->render('index', ['rows' => $rows, 'pager' => $page]);
    }
    public function actionAdd()
    {
        $model = new GoodsCategory();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断是添加顶级分类还是子分类
            if($model->parent_id){
                //添加子分类
                $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                if($parent->depth>=2){
                    \Yii::$app->session->setFlash('danger','最多添加三级分类');
                    //跳转到本页
                    //return $this->redirect(['add']);
                    return $this->refresh();
                }
                //创建子分类
                $model->prependTo($parent);
            }else{
                //添加顶级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','分类添加成功');
            //跳转到本页
            //return $this->redirect(['add']);
            return $this->refresh();
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id)
    {
        $model = GoodsCategory::findOne($id);
        try{
            if($model->load(\Yii::$app->request->post()) && $model->validate()){
                //判断是添加顶级分类还是子分类
                if($model->parent_id){
                    //添加子分类
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    if($parent->depth>=2){
                        \Yii::$app->session->setFlash('danger','最多添加三级分类');
                        //跳转到本页
                        //return $this->redirect(['add']);
                        return $this->refresh();
                    }
                    //创建子分类
                    $model->prependTo($parent);
                }else{
                    if($model->getOldAttribute('parent_id')){
                        $model->makeRoot();
                    }else{
                        $model->save();
                    }
                }
                \Yii::$app->session->setFlash('success','分类修改成功');
                //跳转到本页
                return $this->redirect(['index']);
//                return $this->refresh();
            }
        }catch(Exception $e){
                //            echo $e->getMessage();
            \Yii::$app->session->setFlash('error','不能修改到子分类中');
            return $this->refresh();
        }
                //        exit;
        return $this->render('add',['model'=>$model]);
    }
    public function actionDelete($id){
        $goodsC=Goods::findOne(['goods_category_id'=>$id]);
//        var_dump($goodsC);exit;
        if($goodsC==NULL){

        $obj=GoodsCategory::findOne(['parent_id'=>$id]);
        if($obj==NULL){
            GoodsCategory::deleteAll("id=$id");
            echo TRUE;
        }else{
        \Yii::$app->session->setFlash('danger','删除失败,该节点下有子节点');
        return $this->redirect(['goodscategory/index']);
        }
        }else{
            $url=Url::to(['goods/index']);
            \Yii::$app->session->setFlash('danger',"请先删除属于该分类的商品(包括回收站！)<a href='$url'>查看商品列表</a>");
            return $this->redirect(['goodscategory/index']);
        }
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