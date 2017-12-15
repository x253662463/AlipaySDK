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

        $request = new AlipayConfig();

        $request->setContentd();

        $request->getcontent();

    }

    public function buildFormHtml(alipayCommenContent $content)
    {
        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='" .
            $content->getConfig('gateway_url') . "?charset=".trim($content->getConfig('charset'))."' method='POST'>";
        foreach ($content as $key => $value){
            //TODO:判断字符传是否为空
            $value = str_replace("'","&apos;",$value);
            $sHtml .= "<input type='hidden' name='" . $key . "' value='" . $value . "'/>";
        }
        $sHtml = $sHtml."<input type='submit' value='ok' style='display:none;''></form>";

        $sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";

        return $sHtml;
    }

    public function writeLog($message,$path = '/log')
    {
        $file = $path . '/alipay_' . date('Y-m-d') . '.log';
        file_put_contents($file,date("G:i:s") . " " . $message . "\r\n",FILE_APPEND);
    }

}