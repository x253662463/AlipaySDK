<?php
/**
 * Creator: xie
 * Time: 2017/12/12 10:38
 */

class AlipayApi
{

    public function wapPay($content)
    {
        $content = $content->generateContent();


    }

    public function writeLog($message,$path = '/log')
    {
        $file = $path . '/alipay_' . date('Y-m-d') . '.log';
        file_put_contents($file,date("G:i:s") . " " . $message . "\r\n",FILE_APPEND);
    }

}