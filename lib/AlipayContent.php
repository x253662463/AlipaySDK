<?php

class AlipayContent
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