<?php

class alipayCommenContent
{
    private $config = array();

//    /**
//     * 获取网关url
//     */
//    public function getGatewayUrl()
//    {
//        return $this->config['gateway_url'];
//    }
//
//    /**
//     * 设置网关url
//     */
//    public function setGatewayUrl($url)
//    {
//        $this->config['gateway_url'] = $url;
//    }
//
//    /**
//     * 判断网关url是否设置
//     */
//    public function isSetGatewayUrl()
//    {
//        return array_key_exists('gateway_url', $this->config);
//    }
//
//    /**
//     * 获取编码
//     */
//    public function getCharset()
//    {
//        return $this->config['charset'];
//    }
//
//    /**
//     * 设置编码
//     */
//    public function setCharset($charset)
//    {
//        $this->config['charset'] = $charset;
//    }
//
//    /**
//     * 判断编码是否设置
//     */
//    public function isSetCharset()
//    {
//        return array_key_exists('charset', $this->config);
//    }
}

class alipayRequestContent
{

}

/*
 * 支付宝登陆构造类
 */
class loginContent extends alipayCommenContent{

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