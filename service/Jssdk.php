<?php
/**
 *                          ____
 *  _      _____  _________/ / /__
 * | | /| / / _ \/ ___/ __  / //_/
 * | |/ |/ /  __(__  ) /_/ / ,<
 * |__/|__/\___/____/\__,_/_/|_|
 * @author carolkey <carolkey@wesdk.org>
 * @link https://github.com/carolkey/wesdk
 * @copyright 2017 wesdk
 * @license MIT
 */

namespace wesdk\service;

/**
 * Class Jssdk
 * @package wesdk\service
 */
class Jssdk extends BaseService
{
    /**
     * 返回script标签,用于页面引入jssdk的js文件
     * @return string
     */
    public function js()
    {
        return '<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>';
    }

    /**
     * 刷新jsapi_ticket
     * @return bool|string
     */
    protected function refreshJsApiTicket()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $this->getAccessToken() . '&type=jsapi';
        $return = $this->jsonDecode($this->httpGet($url));
        if ($return = $this->checkReturn($return)) {
            $cacheKey = $this->mp->appId . 'jsapiticket';
            $this->setCache($cacheKey, [
                'expire' => time() + $return['expires_in'] - 200,
                'token' => $return['ticket'],
            ]);
            return $return['ticket'];
        }
        return $return;
    }

    /**
     * 获取jsapi_ticket
     * @return bool|string
     */
    private function getJsApiTicket()
    {
        return $this->getToken('jsapiticket', [$this, 'refreshJsApiTicket']);
    }

    /**
     * 返回注入权限验证配置JSON字符串
     * @param array $apiList 需要使用的JS接口列表,如['onMenuShareAppMessage', 'onMenuShareQQ']
     * @param bool $debug 是否开启调试模式<br>
     * 调用的所有api的返回值会在客户端alert出来<br>
     * 若要查看传入的参数,可以在pc端打开,参数信息会通过log打出,仅在pc端时才会打印
     * @param string $url 指定URL,一般不用,默认为当前url
     * @return string 返回配置JSON字符串
     */
    public function config($apiList, $debug = false, $url = '')
    {
        $appid = $this->mp->appId;
        $jsapiTicket = $this->getJsApiTicket();
        $nonceStr = $this->nonceStr(16);
        $timestamp = time();
        $url = empty($url) ? $this->url() : $url;
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        return $this->jsonEncode([
            'debug' => (boolean)$debug,
            'appId' => $appid,
            'timestamp' => $timestamp,
            'nonceStr' => $nonceStr,
            'signature' => $signature,
            'jsApiList' => $apiList,
        ]);
    }
}
