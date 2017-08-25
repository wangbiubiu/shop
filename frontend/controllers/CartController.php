<?php

namespace frontend\controllers;

use app\models\Cart;
use backend\models\Goods;
use Yii;
use yii\web\Cookie;

class CartController extends \yii\web\Controller
{
    public $enableCsrfValidation=FALSE;
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionNotice($goods_id,$amount){
//        carts
        if(\Yii::$app->user->isGuest){
            $cookies=\Yii::$app->request->cookies;
            $carts = $cookies->getValue('carts');
            if($carts==NULL){
                $carts=[];
            }else{
                $carts=unserialize($carts);
            }
            //1.根据goods_id 去购物车表查询，是否存在该商品
            if(array_key_exists($goods_id,$carts)) {
                //1.1如果已存在，则更新购物车对应的商品数量
                $carts[$goods_id] += $amount;
            }else{
                //1.2如果不存在，则插入一条新数据
                $carts[$goods_id] = $amount;
            }
            $cookies = \Yii::$app->response->cookies;
            //写入数据到cookie
            $cookie = new Cookie([
                                     'name'=>'carts',
                                     'value'=>serialize($carts),
                                     'expire'=>time()+30*24*3600 //过期时间戳 30天
                                 ]);
            $cookies->add($cookie);//设置cookie
        }else{
            $member_id=\Yii::$app->user->identity->id;

            $res=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
            //            如果没有就新增
            if($res==NULL){
            $model=new Cart();
            $model->goods_id=$goods_id;
            $model->member_id=$member_id;
            $model->amount=$amount;
            if($model->validate()){
                $model->save();
            }
            }else{
                $res->amount=$res->amount+$amount;
                $res->save();
            }
        }
        return $this->redirect(['cart/cart']);
    }
    public function actionCart(){
        if(Yii::$app->user->isGuest){
            //未登陆 购物车数据从cookie获取
            $cookies = Yii::$app->request->cookies;
            //1.看cookie中是否有购物车
            $carts = $cookies->getValue('carts');
            if($carts==null){
                $carts = [];
            }else{
                $carts = unserialize($carts);
            }
            $models = Goods::find()->where(['in','id',array_keys($carts)])->all();
//            var_dump($carts);exit;
        }else{
            //登陆 购物数据从数据库获取
            $member_id=\Yii::$app->user->identity->id;
            $column=Cart::find()->select('goods_id')->where(['=','member_id',$member_id])->column();
//            var_dump($models);exit;
            $models = Goods::find()->where(['in','id',$column])->all();
            $cartsModedl=Cart::find()->select('goods_id,amount')->where(['=','member_id',$member_id])->asArray()->all();
            $carts=[];
            foreach($cartsModedl as $key=>$value){
                $carts[$value['goods_id']]=$value['amount'];
            }
        }
        return $this->render('cart',['models'=>$models,'carts'=>$carts]);
    }
    //修改购物车商品数量
    public function actionAjaxCart(){
        //修改cookie中的购物车  goods_id  amount
        $goods_id = Yii::$app->request->post('goods_id');
        $amount = Yii::$app->request->post('amount');
        if(Yii::$app->user->isGuest){
            $cookies = Yii::$app->request->cookies;
            //1.看cookie中是否有购物车
            $carts = $cookies->getValue('carts');
            if($carts==null){
                //                $carts = [];
                return '商品不存在，请刷新页面';
            }else{
                $carts = unserialize($carts);
            }
            //1.根据goods_id 去购物车表查询，是否存在该商品
            if(array_key_exists($goods_id,$carts)) {
                //1.1如果已存在，则更新购物车对应的商品数量
                if($amount==0){
                    //删除
                    unset($carts[$goods_id]);
                }else{
                    $carts[$goods_id] = $amount;
                }

                $cookies = Yii::$app->response->cookies;
                //写入数据到cookie
                $cookie = new Cookie([
                                         'name'=>'carts',
                                         'value'=>serialize($carts),
                                         'expire'=>time()+30*24*3600 //过期时间戳 30天
                                     ]);
                $cookies->add($cookie);//设置cookie
                return 'success';
            }else{
                return '商品不存在，请刷新页面';
            }
        }else{
            $member_id=\Yii::$app->user->identity->id;
            $goodsModel=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
            if($goodsModel==null){
                return '商品不存在，请刷新页面';
            }else{
                if($amount==0){
                    $goodsModel->delete();
                }else{
                    $goodsModel->amount=$amount;
                    $goodsModel->save();
                }
                return 'success';
            }
        }
    }
}
