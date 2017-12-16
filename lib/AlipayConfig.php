<?php

class AlipayConfig
{

    //TODO:所有的商铺信息设置

    //商铺环境，0为沙箱环境，1为正式环境
    const APP_ENVIRONMENT = 0;

    //应用ID,您的APPID。
    const APP_ID = '';

    //商户私钥，您的原始格式RSA私钥
    const MERCHANT_PRIVATE_KEY = '';
    const ALIPAY_PUBLIC_KEY = '';

    const CHARSET = 'utf-8';

    const SIGN_TYPE = 'RSA2';

    const RSA_PRIVATE_KEY_FILE_PATH = '';

    //异步通知地址
    const NOTIFY_URL = '';
    //同步跳转
    const RETURN_URL = '';



}