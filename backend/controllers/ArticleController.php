<?php

namespace backend\controllers;

use backend\models\Ar;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\helpers\Url;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {

        $name=\Yii::$app->request->get('name')?\Yii::$app->request->get('name'):"";
        $intro=\Yii::$app->request->get('intro')?\Yii::$app->request->get('intro'):"";
//        $intro=\Yii::$app->get('intro');
//        var_dump($name);exit;
        $rows=Ar::find()->select('article.*,article_category.name as c_name')->innerJoin('article_category','article.article_category_id=article_category.id')->where("article.status>-1 and article.name like '%$name%' and article.intro like '%$intro%'");
        $page = new Pagination([
                                   //            获取总条数
                                   'totalCount' => $rows->count(),
                                   'defaultPageSize' => 10,
                               ]);
        $rows = $rows->offset($page->offset)
                     ->limit($page->pageSize)
            ->asArray()
                     ->all();
//        分配模型用于显示搜索
        $model=new Ar();
        //2 将数据赋值给视图
        //3 选择视图显示数据
        return $this->render('index', ['rows' => $rows, 'pager' => $page,'model'=>$model]);
    }
//添加
    public function actionAdd()
    {
        $request=\Yii::$app->request;
//        文章信息模型
        $model= new Ar();
//        文章内容模型
        $modelcont=new ArticleDetail();
        if($request->isPost){
            $data=$request->post();
//            var_dump($data);exit;
//            绑定
            $model->load($data);
            $modelcont->load($data);
//            验证
            if($modelcont->validate() and $model->validate()){
                $model->save();
//                获取上次添加的id
                $contentId=$model->id;
//                绑定
                $modelcont->id=$contentId;
                $modelcont->save();
                //            成功跳转
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article/index']);
            }
            \Yii::$app->session->setFlash('danger','添加失败');
            return $this->redirect(['article/add']);
        }
//        显示
        $data=ArticleCategory::find()->where('status>-1')->all();
        return $this->render('add',['model'=>$model,'modelconc'=>$modelcont,'data'=>$data]);
    }
//    修改
    public function actionEdit($id)
    {
        $request=\Yii::$app->request;
        //        文章信息模型
        $model= Ar::findOne($id);
        //        文章内容模型
        $modelcont=ArticleDetail::findOne($id);
        if($request->isPost){
            $data=$request->post();
            //            var_dump($data);exit;
            //            绑定
            $model->load($data);
            $modelcont->load($data);
            //            验证
            if($modelcont->validate() and $model->validate()){
                $model->save();
                //                获取上次添加的id
                $contentId=$model->id;
                //                绑定
                $modelcont->id=$contentId;
                $modelcont->save();
                //            成功跳转
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['article/index']);
            }
            \Yii::$app->session->setFlash('danger','修改失败');
            return $this->redirect(['article/index']);
        }
        //        显示
        $data=ArticleCategory::find()->where('status>-1')->all();
        return $this->render('add',['model'=>$model,'modelconc'=>$modelcont,'data'=>$data]);
    }
//    显示单条内容
    public function actionContent($id){
        //        文章信息模型
        $model= Ar::findOne($id);
        //        文章内容模型
        $modelcont=ArticleDetail::findOne($id);

        return $this->render('content',['title'=>$model,'content'=>$modelcont]);
    }
    public function actionDelete($id){
        //        文章信息模型
        $model= Ar::findOne($id);
        $model->status=-1;
        if($model->validate()){
            $model->save();
            $url=Url::to(['article/re']);
            \Yii::$app->session->setFlash('success', "删除成功:<a href='$url'>查看回收站</a>");
            return $this->redirect(['article/index']);
        }
        \Yii::$app->session->setFlash('danger','删除失败');
        return $this->redirect(['article/index']);
    }
//    回收站
    public function actionRe()
    {
        $rows=Ar::find()->select('article.*,article_category.name as c_name')->innerJoin('article_category','article.article_category_id=article_category.id')->where('article.status=-1');
        $page = new Pagination([
                                   //            获取总条数
                                   'totalCount' => $rows->count(),
                                   'defaultPageSize' => 10,
                               ]);
        $rows = $rows->offset($page->offset)
                     ->limit($page->pageSize)
                     ->asArray()
                     ->all();
        //2 将数据赋值给视图
        //3 选择视图显示数据
        return $this->render('delete', ['rows' => $rows, 'pager' => $page]);
    }
    //    完全删除
    public function actionDeletes($id){
        $res=Ar::deleteAll("id=$id");
        $modelcont=ArticleDetail::deleteAll("id=$id");
        if($res and $modelcont){
            \Yii::$app->session->setFlash( 'success', '删除成功' );
            return $this->redirect(['article/re']);
        }
        \Yii::$app->session->setFlash( 'DANGER', '删除失败' );
        return $this->redirect(['article/re']);
    }
    //    还原
    public function actionRes($id){
        $model=Ar::findOne($id);
        $model->status=0;
        if($model->validate()){
            $model->save();
            $url=Url::to(['article/index']);
            \Yii::$app->session->setFlash( 'success', "数据已还原:<a href='$url'>查看</a>" );
            return $this->redirect(['article/re']);
        }
        \Yii::$app->session->setFlash( 'DANGER', '还原失败' );
        return $this->redirect(['article/re']);
    }
}
