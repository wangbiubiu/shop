<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use yii\data\Pagination;

class IndexController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $models = GoodsCategory::findAll(['parent_id'=>0]);
        return $this->render('index',['models'=>$models]);
    }
    public function actionList($categoryId){
        $cate = GoodsCategory::findOne($categoryId);
        $depth=$cate->depth;//深度
            $son = GoodsCategory::find()->select("id")->andWhere(['=','tree',"$cate->tree"])->andWhere("depth=2")->andWhere(['>','lft',"$cate->lft"])->andWhere(['<','rgt',"$cate->rgt"])->asArray()->all();
            $ids=[];
            foreach($son as $vs){
                foreach($vs as $v){
                    $ids[]=$v;
                }
            }
//            一级二级
        if($depth==1 or $depth==0){
        $models = Goods::find()->where(['in','goods_category_id',$ids]);
            $page = new Pagination([
                                       //            获取总条数
                                       'totalCount' => $models->count(),
                                       'defaultPageSize' => 10,
                                       'pageSizeLimit' => [1,20]
                                   ]);

            $models = $models->offset($page->offset)
                          ->limit($page->pageSize)
                          ->all();

            //2 将数据赋值给视图
            //3 选择视图显示数据
            return $this->render('list', ['models' => $models, 'pager' => $page]);

        }
//        三级
        if($depth==2){
            $models =Goods::find()->where(['goods_category_id'=>$categoryId]);
            $page = new Pagination([
                                       //            获取总条数
                                       'totalCount' => $models->count(),
                                       'defaultPageSize' => 10,
                                       'pageSizeLimit' => [1,20]
                                   ]);

            $models = $models->offset($page->offset)
                             ->limit($page->pageSize)
                             ->all();
            return $this->render('list', ['models' => $models, 'pager' => $page]);
        }
    }
    public function actionGoods($id){
        $model=Goods::findOne($id);
        return $this->render('goods',['model'=>$model]);
    }

}
