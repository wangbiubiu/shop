<?php

namespace backend\controllers;

use backend\filters\AccessFilter;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use flyok666\qiniu\Qiniu;
use flyok666\uploadifive\UploadAction;
use Yii;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\helpers\Url;

class GoodsController extends \yii\web\Controller
{
    //    图片上传器
    public function actions(){
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ],
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //                    $action->output['fileUrl'] = $action->getWebUrl();
                    //                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    //                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    //                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    //                    上传到七牛
                    $config = [
                        'accessKey'=>'mZeZQUtrtQvXcHgp_km7cWDJNymScYJlkbow87rz',
                        'secretKey'=>'f4MOH4ki516F_Uc8FLiMa6bLM1FxKqG9Jc1mr_WT',
                        'domain'=>'http://oukccx2sl.bkt.clouddn.com/',
                        'bucket'=>'shop',
                        'area'=>Qiniu::AREA_HUADONG
                    ];
                    $qiniu = new Qiniu($config);
                    //                    文件名
                    $key = $action->getWebUrl();
                    //                    文件路径
                    $file = $action->getSavePath();
                    //                    上传
                    $qiniu->uploadFile($file,$key);
                    //                    七牛云绝对路径
                    $url = $qiniu->getLink($key);
                    //                    输出
                    $action->output['fileUrl'] = $url;//输出图片地址
                },
            ],
        ];
    }
    public function actionIndex()
    {
        $name=\Yii::$app->request->get('name')?\Yii::$app->request->get('name'):"";
        $sn=\Yii::$app->request->get('sn')?\Yii::$app->request->get('sn'):"";
        $minPrice=\Yii::$app->request->get('minPrice')?\Yii::$app->request->get('minPrice'):-1;
        $maxPrice=\Yii::$app->request->get('maxPrice')?\Yii::$app->request->get('maxPrice'):1000000000000000;
        $rows = Goods::find()->andwhere('status>-1')->andWhere(['>=','shop_price',$minPrice])->andWhere(['<=','shop_price',$maxPrice])->andWhere(['like','name',$name])->andWhere(['like','sn',$sn]);
        $page = new Pagination([
                                   //            获取总条数
                                   'totalCount' => $rows->count(),
                                   'defaultPageSize' => 5,
                               ]);
        $rows = $rows->offset($page->offset)
                     ->limit($page->pageSize)
                     ->all();
        //2 将数据赋值给视图
        //3 选择视图显示数据
        return $this->render('index', ['rows' => $rows, 'pager' => $page]);
    }
//    添加
    public function actionAdd(){
        $GContentModel=new GoodsIntro();
        $model = new Goods();
        $request=\Yii::$app->request;
        //        判定是否post请求
        if($request->isPost){
            //            接收数据
            $data=$request->post();
            //            成功就绑定数据
//                        var_dump($data);exit;
            $gc_id=$data['Goods']['goods_category_id'];
            $gcGoodsCategory_depth=GoodsCategory::findOne(['id'=>$gc_id])->toArray();
            if($gcGoodsCategory_depth['depth']!=2){
                \Yii::$app->session->setFlash('danger','只能添加到第三级分类');
                return $this->refresh();
            }
            $model->load($data);
            $GContentModel->load($data);
            if($model->validate() and $GContentModel->validate()){
                $day=date("Ymd");
//                如果没有记录就新增记录
                $res=GoodsDayCount::findOne(['day'=>$day]);
                if($res==null){
                    $num=1;
                $num=str_pad($num,5,"0",STR_PAD_LEFT);
//                生成货号
                $sn= date("Ymd").$num;
//                生成新的商品添加记录
                    $GoodsdayModel=new GoodsDayCount;
                    $GoodsdayModel->day=$day;
                    $GoodsdayModel->count=$num;
                    $GoodsdayModel->save();
                }else{
//                    否则查询出来修改记录
                    $num2=$res->count+1;
                    $num2=str_pad($num2,5,"0",STR_PAD_LEFT);
//                    +1货号
                    $sn= date("Ymd").$num2;
//                    修改记录
                    $res->count=$num2;
                    $res->save();
                }
                $model->sn=$sn;
                //            添加数据
                $model->save();
                $contentId=$model->id;
                $GContentModel->goods_id=$contentId;
                $GContentModel->save();
                //                提交
                \Yii::$app->session->setFlash('success','添加成功');
                //            然后跳转
                return $this->redirect(['goods/index']);
            }else{
                //                var_dump($brandMode->getErrors());exit;
                //                否则提示失败
                \Yii::$app->session->setFlash('danger',$model->getErrors());
                return $this->redirect(['goods/add']);
            }
        }
//        商品分类
        $dataGoodsC=GoodsCategory::find()->all();
//        品牌分类
        $dataBrand=Brand::find()->where('status>-1')->all();
        //        1显示添加页面
        return $this->render('add',['model'=>$model,'dataGoodsC'=>$dataGoodsC,'dataBrand'=>$dataBrand,'GContentModel'=>$GContentModel]);
    }
//    修改
    public function actionEdit($id){
        $GContentModel=GoodsIntro::findOne($id);
        $model = Goods::findOne($id);
        $request=\Yii::$app->request;
        //        判定是否post请求
        if($request->isPost){
            //            接收数据
            $data=$request->post();
            $gc_id=$data['Goods']['goods_category_id'];
            $gcGoodsCategory_depth=GoodsCategory::findOne(['id'=>$gc_id])->toArray();
            if($gcGoodsCategory_depth['depth']!=2){
                \Yii::$app->session->setFlash('danger','只能修改到第三级分类');
                return $this->refresh();
            }
            //            成功就绑定数据
            //            var_dump($data);exit;
            $model->load($data);
            $GContentModel->load($data);
            if($model->validate() and $GContentModel->validate()){
                //            添加数据
                $model->save();
                $contentId=$model->id;
                $GContentModel->goods_id=$contentId;
                $GContentModel->save();
                //                提交
                \Yii::$app->session->setFlash('success','修改成功');
                //            然后跳转
                return $this->redirect(['goods/index']);
            }else{
                //                var_dump($brandMode->getErrors());exit;
                //                否则提示失败
                \Yii::$app->session->setFlash('danger',$model->getErrors());
                return $this->redirect(['goods/index']);
            }
        }
        //        商品分类
        $dataGoodsC=GoodsCategory::find()->all();
        //        品牌分类
        $dataBrand=Brand::find()->where('status>-1')->all();
        //        1显示添加页面
        return $this->render('add',['model'=>$model,'dataGoodsC'=>$dataGoodsC,'dataBrand'=>$dataBrand,'GContentModel'=>$GContentModel]);
    }

    public function actionRe()
    {
        $rows = Goods::find()->where('status=-1');
        $page = new Pagination([
                                   //            获取总条数
                                   'totalCount' => $rows->count(),
                                   'defaultPageSize' => 5,
                               ]);
        $rows = $rows->offset($page->offset)
                     ->limit($page->pageSize)
                     ->all();
        //2 将数据赋值给视图
        //3 选择视图显示数据
        return $this->render('delete', ['rows' => $rows, 'pager' => $page]);
    }
//    删除
    public function actionDelete($id){
        //        文章信息模型
        $model= Goods::findOne($id);
        $model->status=-1;
        if($model->validate()){
            $model->save();
            echo TRUE;
        }
    }
    //    完全删除
    public function actionDeletes($id){
        $res=Goods::deleteAll("id=$id");
        $modelcont=GoodsIntro::deleteAll("goods_id=$id");
        if($res and $modelcont){
            echo TRUE;
        }
    }
    public function actionRes($id){
        $model=Goods::findOne($id);
        $model->status=0;
        if($model->validate()){
            $model->save();
            $url=Url::to(['goods/index']);
            \Yii::$app->session->setFlash( 'success', "数据已还原:<a href='$url'>查看</a>" );
            return $this->redirect(['goods/re']);
        }
        \Yii::$app->session->setFlash( 'DANGER', '还原失败' );
        return $this->redirect(['goods/re']);
    }
    public function actionContent($id){
        $title=Goods::findOne($id);
        $content=GoodsIntro::findOne($id);
        $img=GoodsGallery::findAll(['goods_id'=>$id]);
//        var_dump($img);exit;
        return $this->render('content',['title'=>$title,'content'=>$content,'img'=>$img]);
    }
//    显示相册
    public function actionAlbum($id){
        $data=Goods::findOne($id);
        $model=GoodsGallery::findAll(['goods_id'=>$id]);
        return $this->render('album',['data'=>$data,'model'=>$model]);
    }
//    添加照片
    public function actionAjax(){
        $model=new GoodsGallery();
        $request=\Yii::$app->request;
        if($request->isGet){
            $goods_id=$request->get("goods_id");
            $path=$request->get("path");
            $model->goods_id=$goods_id;
            $model->path=$path;
            $model->save();
        }
//        返回数据
        $data=GoodsGallery::findAll(['goods_id'=>$goods_id]);
        echo Json::encode($data);
    }
//    删除照片
    public function actionDel(){
        $request=\Yii::$app->request;
        $id=$request->get("id");
        $data=GoodsGallery::findOne($id);
        $data->delete();
        $goods_id=$request->get("goods_id");
        $data=GoodsGallery::findAll(['goods_id'=>$goods_id]);
        echo Json::encode($data);
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