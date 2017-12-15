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
        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='".$content->getGatewayUrl()."?charset=".trim($this->postCharset)."' method='POST'>";
        while (list ($key, $val) = each ($para_temp)) {
            if (false === $this->checkEmpty($val)) {
                //$val = $this->characet($val, $this->postCharset);
                $val = str_replace("'","&apos;",$val);
                //$val = str_replace("\"","&quot;",$val);
                $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
            }
        }

        //submit按钮控件请不要含有name属性
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