<?php

class alipayCommenContent
{
    //公共请求参数


    protected $gatewayUrl = "";

    /*
     * 公共请求参数，
     */
    private $commonParams = array();

    /**
     * 设置公共请求参数
     * @param $key
     * @param $value
     */
    public function setCommonParams($key, $value)
    {
        $this->commonParams[$key] = $value;
    }

    /**
     * 读取配置
     * @param $key
     * @return mixed
     */
    public function getCommonParams($key)
    {
        return $this->commonParams[$key];
    }

    /**
     * 判断配置是否有配置
     * @param $key
     * @return mixed
     */
    public function isSetCommonParams($key)
    {
        return $this->commonParams[$key];
    }

    /**
     * 设置网关请求地址
     * @param string $gateway_url
     */
    public function setGatewayUrl($gateway_url)
    {
        $this->gateway_url = $gateway_url;
    }
}

class alipayRequestContent
{

}

/*
 * 支付宝登陆构造类
 */
class alipayAuthContent extends alipayCommenContent{

    protected $gatewayUrl = "https://openapi.alipay.com/gateway.do";

    /*
     * 公共请求参数包括：
     * app_id,method,format,return_url,charset,sign_type,sign,
     * timestamp,version,app_auth_token,biz_content
     */
    private $commonParamsArray = array(
        'app_id' => '',
        'method' => 'alipay.user.info.auth',
        'format' => 'JSON',
        'return_url' => '',
        'charset' => '',
        'sign_type' => '',
        'sign' => '',
        'timestamp' => '',
        'version' => '1.0',
        'app_auth_token' => '',
        'biz_content' => ''
    );

    /*
     * 请求参数包括scopes,state
     */
    private $params = array();

    public function __construct()
    {
        foreach ($this->commonParamsArray as $key => $param){
            $this->setCommonParams($key,$param);
        }
    }

}

/*
 * 支付宝手机支付构造类
 */
class wapPayContent extends alipayCommenContent
{
    private $content;

    public function __construct()
    {
        $this->content['productCode'] = "QUICK_WAP_PAY";
    }

    public function generateContent()
    {
        if (!empty($this->content)){
            $this->content = json_encode($this->content,JSON_UNESCAPED_UNICODE);
        }
        return $this->content;
    }

    public function setBody($body)
    {
        $this->content['body'] = $body;
    }

    public function setSubject($subject)
    {
        $this->content['subject'] = $subject;
    }

    public function setOutTradeNo($outTradeNo)
    {
        $this->content['out_trade_no'] = $outTradeNo;
    }

    public function setTimeExpress($timeExpress)
    {
        $this->content['timeout_express'] = $timeExpress;
    }

    public function setTotalAmount($totalAmount)
    {
        $this->content['total_amount'] = $totalAmount;
    }

    public function setSellerId($sellerId)
    {
        $this->content['seller_id'] = $sellerId;
    }
}