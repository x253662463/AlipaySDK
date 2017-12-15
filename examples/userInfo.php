<?php
/**
 * Creator: xie
 * Time: 2017/12/15 14:52
 */

$alipayApi = new AlipayApi();

$alipayAuth = new alipayAuthContent();

$alipayAuth->setGatewayUrl("https://openapi.alipay.com/gateway.do");

/*
 * app_id,method,format,return_url,charset,sign_type,sign,
     * timestamp,version,app_auth_token,biz_content
 */
$alipayAuth->setCommonParams('app_id','');
$alipayAuth->setCommonParams('return_url','');
$alipayAuth->setCommonParams('charset','utf-8');
$alipayAuth->setCommonParams('sign_type','RSA2');
$alipayAuth->setCommonParams('sign','');
$alipayAuth->setCommonParams('timestamp',date("Y-m-d H:i:s"));
$alipayAuth->setCommonParams('app_auth_token','');
