<?php
namespace frontend\components;
use yii\base\Component;
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;

class Sms extends Component{
    public $ak;
    public $sk;
    public $sign;//短信签名
    public $template;//模板id

    //要发送的手机号码
    public $number;
    //替换变量
    public $params=[];//${code}    [code=>1234,name=>'222']

    private $acsClient;

    public function init()
    {
        // 加载区域结点配置
        Config::load();
        // 短信API产品名
        $product = "Dysmsapi";

        // 短信API产品域名
        $domain = "dysmsapi.aliyuncs.com";

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";

        // 初始化用户Profile实例
        $profile = DefaultProfile::getProfile($region, $this->ak,$this->sk);

        // 增加服务结点
        DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

        // 初始化AcsClient用于发起请求
        $this->acsClient = new DefaultAcsClient($profile);
        parent::init();
    }

    public function send(){
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($this->number);

        // 必填，设置签名名称
        $request->setSignName($this->sign);

        // 必填，设置模板CODE
        $request->setTemplateCode($this->template);

        // 可选，设置模板参数
        if($this->params) {
            $request->setTemplateParam(json_encode($this->params));
        }

        // 可选，设置流水号
        /*if($outId) {
            $request->setOutId($outId);
        }*/

        // 发起访问请求
        $acsResponse = $this->acsClient->getAcsResponse($request);

        // 打印请求结果
        // var_dump($acsResponse);

        return $acsResponse;
    }

    public function setNumber($value){
        $this->number = $value;
        return $this;
    }

    public function setParams(Array $data){
        $this->params = $data;
        return $this;
    }

}