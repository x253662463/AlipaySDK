<?php

/**
 * 参数请求类，每一个类对应一个请求接口
 */

/**
 * Class alipayContent
 * 公共参数类
 *
 */
abstract class alipayContent
{
    //所有公共请求参数

    //系统公用参数
    protected $commonParams = array();

    //请求参数
    protected $bizContent = array();

    //接口必须参数
    protected $checkRequired = array();

    //支付宝默认网关
    protected $gateway = array(
        0 => 'https://openapi.alipaydev.com/gateway.do',
        1 => 'https://openapi.alipay.com/gateway.do'
    );

    /**
     * @return mixed
     */
    public function getAppId()
    {
        return $this->commonParams['app_id'];
    }

    /**
     * @param mixed $app_id
     */
    public function setAppId($app_id)
    {
        $this->commonParams['app_id'] = $app_id;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->commonParams['method'];
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->commonParams['method'] = $method;
    }

    /**
     * @return mixed
     */
    public function getFormat()
    {
        return $this->commonParams['format'];
    }

    /**
     * @param mixed $format
     */
    public function setFormat($format)
    {
        $this->commonParams['format'] = $format;
    }

    /**
     * @return mixed
     */
    public function getCharset()
    {
        return $this->commonParams['charset'];
    }

    /**
     * @param mixed $charset
     */
    public function setCharset($charset)
    {
        $this->commonParams['charset'] = $charset;
    }

    /**
     * @return mixed
     */
    public function getSignType()
    {
        return $this->commonParams['sign_type'];
    }

    /**
     * @param mixed $sign_type
     */
    public function setSignType($sign_type)
    {
        $this->commonParams['sign_type'] = $sign_type;
    }

    /**
     * @return mixed
     */
    public function getSign()
    {
        return $this->commonParams['sign'];
    }

    /**
     * @param mixed $sign
     */
    public function setSign($sign)
    {
        $this->commonParams['sign'] = $sign;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->commonParams['timestamp'];
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->commonParams['timestamp'] = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->commonParams['version'];
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->commonParams['version'] = $version;
    }

    /**
     * @return mixed
     */
    public function getAppAuthToken()
    {
        return $this->commonParams['app_auth_token'];
    }

    /**
     * @param mixed $app_auth_token
     */
    public function setAppAuthToken($app_auth_token)
    {
        $this->commonParams['app_auth_token'] = $app_auth_token;
    }

    /**
     * @return mixed
     */
    public function getBizContent()
    {
        return $this->commonParams['biz_content'];
    }

    /**
     * @param mixed $biz_content
     */
    public function setBizContent($biz_content)
    {
        $this->commonParams['biz_content'] = $biz_content;
    }

    /**
     * @return array
     */
    public function getCommonParams()
    {
        return $this->commonParams;
    }

    public function generateBizContent()
    {
        $this->commonParams['biz_content'] = json_encode($this->bizContent,256);
    }

    public function __construct()
    {
        $this->setAppId(AlipayConfig::APP_ID);
        $this->setFormat('JSON');
        $this->setCharset(AlipayConfig::CHARSET);
        $this->setSignType(AlipayConfig::SIGN_TYPE);
        $this->setVersion('1.0');
    }

    /**
     * 检测列表判断参数是否存在，不存在报错
     * @throws Exception
     */
    public function checkParams()
    {
        foreach ($this->checkRequired as $key => $value){
            if (!array_key_exists($value,$this->commonParams)){
                //TODO:优化错误提醒
                echo "error," . $value . " can not be null;";exit;
//                throw new Exception($value . "为必填参数");
            }
        }
    }

    public function getSignContent() {
        ksort($this->commonParams);

        return $this->ToUrlParams($this->commonParams);
    }

    /**
     * 将传入参数转为url参数字符串
     * @param $params
     * @return string
     */
    public function ToUrlParams($params)
    {
        $buff = "";
        foreach ($params as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 生成签名sign
     * @param $data
     * @param string $signType
     * @return string
     */
    protected function sign($data, $signType = "RSA") {
        //TODO:秘钥什么的走配置接口
        if($this->checkEmpty(AlipayConfig::RSA_PRIVATE_KEY_FILE_PATH)){
            $priKey=AlipayConfig::MERCHANT_PRIVATE_KEY;
            $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
                wordwrap($priKey, 64, "\n", true) .
                "\n-----END RSA PRIVATE KEY-----";
        }else {
            $priKey = file_get_contents($this->commonParams['rsaPrivateKeyFilePath']);
            $res = openssl_get_privatekey($priKey);
        }

        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');

        //TODO:5.4之前不支持第四个参数
        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $res, "sha256");
        } else {
            openssl_sign($data, $sign, $res);
        }

        if(!$this->checkEmpty($this->commonParams['rsaPrivateKeyFilePath'])){
            openssl_free_key($res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }


    public function generateSign()
    {
        $this->generateBizContent();
        $this->commonParams['sign'] = $this->sign($this->getSignContent(),$this->getSignType());
    }

    /**
     * 判断值是否为空
     * @param $value
     * @return bool
     */
    public function checkEmpty($value)
    {
        if (!isset($value) || $value === null || trim($value) === ""){
            return true;
        }
        return false;
    }

    /**
     * 生成请求url
     * @return string
     */
    public function generateUrl()
    {
        $request_url = $this->gateway[AlipayConfig::APP_ENVIRONMENT] . '?';
        foreach ($this->commonParams as $key => $val){
            $request_url .= "$key=" . urlencode($val) . "&";
        }
        $request_url = trim($request_url,'&');
        return $request_url;
    }


}

/*
 * 支付API开始
 */

// alipay.trade.fastpay.refund.query(统一收单交易退款查询)
class alipayTradeFastpayRefundQuery extends alipayContent{

}

// alipay.trade.order.settle(统一收单交易结算接口)
class alipayTradeOrderSettle extends alipayContent{

}

// alipay.trade.close(统一收单交易关闭接口)
class alipayTradeClose extends alipayContent{

}

// alipay.trade.cancel(统一收单交易撤销接口)
class alipayTradeCancel extends alipayContent{

}

// alipay.trade.refund(统一收单交易退款接口)

class alipayTradeRefund extends alipayContent{

}

// alipay.trade.precreate(统一收单线下交易预创建)
class alipayTradePrecreate extends alipayContent{

}
// alipay.trade.create(统一收单交易创建接口)
class alipayTradeCreate extends alipayContent{

}

// alipay.trade.pay(统一收单交易支付接口)
class alipayTradePay extends alipayContent{

}

// alipay.trade.query(统一收单线下交易查询)
class alipayTradeQuery extends alipayContent{

}
/*
 * 支付API结束
 */


/*
 * 工具类Api开始
 */

//用户进行账密、扫码登陆并授权alipay.user.info.auth
class alipayUserInfoAuth extends alipayContent{

}

// 查询某个应用授权AppAuthToken的授权信息alipay.open.auth.token.app.query
class alipayOpenAuthTokenAppQuery extends alipayContent{

}

// 换取应用授权令牌，即app_auth_code换取app_auth_token
class alipayAuthTokenApp extends alipayContent {

    /**
     * @return mixed
     */
    public function getGrantType()
    {
        return $this->bizContent['grant_type'];
    }

    /**
     * @param mixed $grant_type
     */
    public function setGrantType($grant_type)
    {
        $this->bizContent['grant_type'] = $grant_type;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->bizContent['code'];
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->bizContent['code'] = $code;
    }

    /**
     * @return mixed
     */
    public function getRefreshToken()
    {
        return $this->bizContent['refresh_token'];
    }

    /**
     * @param mixed $refresh_token
     */
    public function setRefreshToken($refresh_token)
    {
        $this->bizContent['refresh_token'] = $refresh_token;
    }

    public function __construct()
    {
        parent::__construct();
        $this->setMethod('alipay.open.auth.token.app');
        $this->checkRequired = array('app_id','method','format','charset','sign_type', 'timestamp','version');
    }
}

// alipay.offline.market.reporterror.create(上报线下服务异常)
class alipayOfflineMarketReporterrorCreate extends alipayContent{

}

// alipay.system.oauth.token(换取授权访问令牌)
class alipaySystemOauthToken extends alipayContent{

}

// koubei.member.data.oauth.query(口碑业务授权令牌查询)
class koubeiMemberDateOauthQuery extends alipayContent{

}

// monitor.heartbeat.syn(验签接口)
class monitorHeartbeatSyn extends alipayContent{

}

/*
 * 工具类Api结束
 */