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
 * Class OAuth
 * @package wesdk\service
 */
class OAuth extends BaseService
{
    /**
     * 跳转到网页授权页面
     * @param boolean $userInfo 是否使用snsapi_userinfo方式授权,默认否
     * @param string $redirectUrl 授权后重定向的回调链接地址,默认当前url
     * @param string $state 重定向后会带上state参数,开发者可以填写a-zA-Z0-9的参数值,最多128字节
     */
    public function auth($userInfo = false, $redirectUrl = '', $state = '')
    {
        $redirectUrl = empty($redirectUrl) ? $this->url() : $redirectUrl;
        $params = $this->buildParams([
            'appid' => $this->mp->appId,
            'redirect_uri' => urlencode($redirectUrl),
            'response_type' => 'code',
            'scope' => ($userInfo ? 'snsapi_userinfo' : 'snsapi_base'),
            'state' => $state
        ]);
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?' . $params. '#wechat_redirect';
        header("Location: $url");
        exit(0);
    }

    /**
     * 获取授权回调的code参数
     * @return string|null 不存在返回null
     */
    public function getCode()
    {
        return $this->query('code');
    }

    /**
     * 获取用户信息,不存在返回false
     * @param string $code 通过getCode()获取到的code
     * @return array|bool|UserInfo
     */
    public function user($code)
    {
        $return = $this->getAuthAccessToken($code);
        if ($return) {
            if ($return['scope'] == 'snsapi_base') {
                $info['openid'] = $return['openid'];
                if (isset($return['unionid'])) {
                    $info['unionid'] = $return['unionid'];
                }
                $return = new UserInfo($info);
            } elseif ($return['scope'] == 'snsapi_userinfo') {
                $return = $this->getUserInfo($return['access_token'], $return['openid']);
                if ($return) {
                    $return = new UserInfo($return);
                }
            }
        }
        return $return;
    }

    /**
     * 通过code换取网页授权access_token
     * @param string $code 用户授权带上的code
     * @return array|bool
     */
    private function getAuthAccessToken($code)
    {
        $params = $this->buildParams([
            'appid' => $this->mp->appId,
            'secret' => $this->mp->appSecret,
            'code' => $code,
            'grant_type' => 'authorization_code'
        ]);
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?' . $params;
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 拉取用户信息(需scope为snsapi_userinfo)
     * @param string $accessToken 网页授权的access_token
     * @param string $openid 用户的唯一标识
     * @return array|bool
     */
    private function getUserInfo($accessToken, $openid)
    {
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $accessToken . '&openid=' . $openid . '&lang=zh_CN';
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }
}
