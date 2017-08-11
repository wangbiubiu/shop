<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
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
            $brandMode->logoImg=UploadedFile::getInstance($brandMode,'logoImg');
//            验证
            if($brandMode->validate()){
            //保存上传文件
//                var_dump(!$brandMode->logoImg==NULL);exit;
                if(!$brandMode->logoImg==NULL){
                    $fileName = '/upload/' . uniqid() . '.' . $brandMode->logoImg->extension;
                    if( $brandMode->logoImg->saveAs( \Yii::getAlias( '@webroot' ) . $fileName, FALSE ) ){
                        $brandMode->logo = $fileName;
                    }
                }
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
            $brandMode->logoImg=UploadedFile::getInstance($brandMode,'logoImg');
            //            验证
            if($brandMode->validate()){
                //保存上传文件
                if($brandMode->logoImg!==NULL){
                    $fileName = '/upload/' . uniqid() . '.' . $brandMode->logoImg->extension;
                    if( $brandMode->logoImg->saveAs( \Yii::getAlias( '@webroot' ) . $fileName, FALSE ) ){
                        $brandMode->logo = $fileName;
                    }
                }
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
                return $this->redirect(['brand/add']);
            }
        }
        //        1显示添加页面
        return $this->render('add',['model'=>$brandMode]);
    }
    public function actionDelete($id){
        //        echo  $id;
        $brandMode = Brand::findOne( [ 'id' => $id ] );
        //        修改状态
        $brandMode->status = -1;
        //        添加成功跳转
        if( $brandMode->validate() ){
            //        提交
            \Yii::$app->session->setFlash( 'success', '删除成功' );
            $brandMode->save();
            return $this->redirect(['brand/index']);
        }
//        失败提示
        \Yii::$app->session->setFlash('danger',$brandMode->getErrors());
        return $this->redirect(['brand/add']);
    }
}
