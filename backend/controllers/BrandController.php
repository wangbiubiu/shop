<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use yii\data\Pagination;
use yii\helpers\Url;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;

class BrandController extends \yii\web\Controller
{
//    图片上传器
    public function actions(){
        return [
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

    //    列表页
    public function actionIndex()
    {
        $rows = Brand::find()->where('status>-1');
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
//    添加品牌
    public function actionAdd(){
//        公共的放上面
        $brandMode=new Brand();

        $request=\Yii::$app->request;
//        判定是否post请求
        if($request->isPost){
//            接收数据
            $data=$request->post();
            //            成功就绑定数据
//            var_dump($data);exit;
            $brandMode->load($data);
//            文件上传类单例模式
//            $brandMode->logoImg=UploadedFile::getInstance($brandMode,'logoImg');
//            验证
            if($brandMode->validate()){
            //保存上传文件
//                var_dump(!$brandMode->logoImg==NULL);exit;
//                if(!$brandMode->logoImg==NULL){
//                    $fileName = '/upload/' . uniqid() . '.' . $brandMode->logoImg->extension;
//                    if( $brandMode->logoImg->saveAs( \Yii::getAlias( '@webroot' ) . $fileName, FALSE ) ){
//                        $brandMode->logo = $fileName;
//                    }
//                }
//            添加数据
                $brandMode->save();
//                提交
            \Yii::$app->session->setFlash('success','添加成功');
//            然后跳转
            return $this->redirect(['brand/index']);
            }else{
//                var_dump($brandMode->getErrors());exit;
//                否则提示失败
                \Yii::$app->session->setFlash('danger',$brandMode->getErrors());
                return $this->redirect(['brand/add']);
            }
        }
//        1显示添加页面
        return $this->render('add',['model'=>$brandMode]);
    }
    //    添加品牌
    public function actionEdit($id){
        //        公共的放上面
        $brandMode = Brand::findOne(['id'=>$id]);

        $request=\Yii::$app->request;
        //        判定是否post请求
        if($request->isPost){
            //            接收数据
            $data=$request->post();
            //            成功就绑定数据
            //            var_dump($data);exit;
            $brandMode->load($data);
            //            文件上传类单例模式
//            $brandMode->logoImg=UploadedFile::getInstance($brandMode,'logoImg');
            //            验证
            if($brandMode->validate()){
                //保存上传文件
//                if($brandMode->logoImg!==NULL){
//                    $fileName = '/upload/' . uniqid() . '.' . $brandMode->logoImg->extension;
//                    if( $brandMode->logoImg->saveAs( \Yii::getAlias( '@webroot' ) . $fileName, FALSE ) ){
//                        $brandMode->logo = $fileName;
//                    }
//                }
                //            添加数据
                $brandMode->save();
                //                提交
                \Yii::$app->session->setFlash('success','修改成功');
                //            然后跳转
                return $this->redirect(['brand/index']);
            }else{
                //                var_dump($brandMode->getErrors());exit;
                //                否则提示失败
                \Yii::$app->session->setFlash('danger',$brandMode->getErrors());
                return $this->redirect(['brand/index']);
            }
        }
        //        1显示添加页面
        return $this->render('add',['model'=>$brandMode]);
    }
    public function actionDelete($id){
        $goodsB=Goods::findOne(['brand_id'=>$id]);
        if($goodsB==NULL){

        //        echo  $id;
        $brandMode = Brand::findOne( [ 'id' => $id ] );
        //        修改状态
        $brandMode->status = -1;
        //        添加成功跳转
        if( $brandMode->validate() ){
            //        提交
            $url=Url::to(['brand/re']);
            $brandMode->save();
            echo TRUE;
        }
        }else{
            $url=Url::to(['goods/index']);
            \Yii::$app->session->setFlash('danger',"请先删除属于该分类的商品(包括回收站！)<a href='$url'>查看商品列表</a>");
            return $this->redirect(['brand/index']);
        }
    }
//    显示回收站
    public function actionRe(){
        $rows = Brand::find()->where('status=-1');
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
        return $this->render('del', ['rows' => $rows, 'pager' => $page]);
    }
//    完全删除
    public function actionDeletes($id){
        $res=Brand::deleteAll("id=$id");
        if($res){
//            return $this->redirect(['brand/re']);
            echo TRUE;
        }
    }
//    还原
    public function actionRes($id){
        $model=Brand::findOne($id);
        $model->status=0;
        if($model->validate()){
            $model->save();
            $url=\yii\helpers\Url::to(['brand/index']);
            \Yii::$app->session->setFlash( 'success', "数据已还原:<a href='$url'>查看</a>" );
            return $this->redirect(['brand/re']);
        }
        \Yii::$app->session->setFlash( 'DANGER', '还原失败' );
        return $this->redirect(['brand/re']);
    }
}
