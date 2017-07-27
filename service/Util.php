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
 * Class Util
 * @package wesdk\service
 */
class Util extends BaseService
{
    /**
     * API调用次数清零
     * @return array|bool
     */
    public function clearApiTimes() {
        $url = 'https://api.weixin.qq.com/cgi-bin/clear_quota?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['appid'=>$this->mp->appId])));
        return $this->checkReturn($return);
    }

    /**
     * 创建临时二维码
     * @param int|string $scene 二维码参数,int型或者string型,数字字符串会被判断为string型参数
     * @param int $ttl 二维码有效秒数,默认2592000,即30天,不填默认为60秒
     * @return array|bool
     */
    public function createTmpQrcode($scene, $ttl = 0)
    {
        $typeInt = is_int($scene);
        $data = [
            'expire_seconds' => $ttl,
            'action_name' => $typeInt ? 'QR_SCENE' : 'QR_STR_SCENE',
            'action_info' => [
                'scene' => [
                    ($typeInt ? 'scene_id' : 'scene_str') => $scene
                ],
            ],
        ];
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode($data)));
        return $this->checkReturn($return);
    }

    /**
     * 创建永久二维码
     * @param int|string $scene
     * @return array|bool
     */
    public function createFixQrcode($scene)
    {
        $typeInt = is_int($scene);
        $data = [
            'action_name' => $typeInt ? 'QR_LIMIT_SCENE' : 'QR_LIMIT_STR_SCENE',
            'action_info' => [
                'scene' => [
                    ($typeInt ? 'scene' : 'scene_str') => $scene
                ],
            ],
        ];
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode($data)));
        return $this->checkReturn($return);
    }

    /**
     * 通过ticket换取二维码链接
     * @param string $ticket 生成二维码返回的ticket
     * @return string
     */
    public function getQrcodeUrl($ticket)
    {
        return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode($ticket);
    }

    /**
     * 长链接转短链接
     * @param string $longUrl 需要转换的长链接,支持http://或https://或weixin://wxpay格式的url
     * @return array|bool
     */
    public function shortUrl($longUrl)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/shorturl?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'action' => 'long2short',
            'long_url' => $longUrl,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 语义理解
     * @param string|array $json
     * @return array|bool
     */
    public function semantic($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/semantic/semproxy/search?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }
}
