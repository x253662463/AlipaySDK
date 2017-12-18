<?php
/**
 * Creator: xie
 * Time: 2017/12/12 10:38
 */
require_once 'AlipayConfig.php';

class AlipayApi
{

    //支付宝登陆授权链接
    private $auth_url = array(
        0 => 'https://openauth.alipaydev.com/oauth2/appToAppAuth.htm',//沙箱环境
        1 => 'https://openauth.alipay.com/oauth2/appToAppAuth.htm'
    );

    /**
     * 生成支付宝授权链接
     * @param $open_id
     * @param $redirect_url
     * @return string
     */
    public function getRequestCodeURL($open_id, $redirect_url)
    {
        $url = $this->auth_url[AlipayConfig::APP_ENVIRONMENT];
        $url .= "?app_id=" . $open_id;
        $url .= "&redirect_uri=" . $redirect_url;
        return $url;
    }

    /**
     * 用code换取auth_token
     * @param $code
     */
    public function getAuthToken(alipayContent $alipayContent)
    {
        $alipayContent->checkParams();

        $alipayContent->generateSign();

        $request_url = $alipayContent->generateUrl();

        $resp = $this->curl($request_url,$alipayContent->getBizContent());

        header("Content-type: text/html; charset=utf-8");
        echo $resp;exit;
    }

    /**
     * 公用curl请求
     * @param $url
     * @param null $postFields
     * @return mixed
     * @throws Exception
     */
    protected function curl($url, $postFields = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $postBodyString = "";
        $encodeArray = Array();
        $postMultipart = false;


        if (is_array($postFields) && 0 < count($postFields)) {

            foreach ($postFields as $k => $v) {
                //判断是不是文件上传
                if ("@" != substr($v, 0, 1)) {
                    $postBodyString .= "$k=" . urlencode($v) . "&";
                    $encodeArray[$k] = $v;
                } else {
                    //文件上传用multipart/form-data，否则用www-form-urlencoded
                    $postMultipart = true;
                    $encodeArray[$k] = new \CURLFile(substr($v, 1));
                }

            }
            unset ($k, $v);
            curl_setopt($ch, CURLOPT_POST, true);
            if ($postMultipart) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $encodeArray);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
            }
        }

        if ($postMultipart) {

            $headers = array('content-type: multipart/form-data;charset=' . AlipayConfig::CHARSET . ';boundary=' . $this->getMillisecond());
        } else {

            $headers = array('content-type: application/x-www-form-urlencoded;charset=' . AlipayConfig::CHARSET);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $reponse = curl_exec($ch);

        if (curl_errno($ch)) {

            throw new Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception($reponse, $httpStatusCode);
            }
        }

        curl_close($ch);
        return $reponse;
    }

    function characet($data, $targetCharset) {

        if (!empty($data)) {
            $fileType = AlipayConfig::CHARSET;
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
                //				$data = iconv($fileType, $targetCharset.'//IGNORE', $data);
            }
        }
        return $data;
    }

    /**
     * 获取时间戳的毫秒数
     * @return float
     */
    protected function getMillisecond() {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    public function buildFormHtml($content)
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