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
 * Class User
 * @package wesdk\service
 */
class User extends BaseService
{
    /**
     * 设置用户备注名
     * @param string $openid 用户标识
     * @param string $remark 新的备注名,长度必须小于30字符
     * @return array|bool
     */
    public function updateRemark($openid, $remark)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'openid' => $openid,
            'remark' => $remark
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 获取用户基本信息
     * @param string $openid 普通用户的标识,对当前公众号唯一
     * @return array|bool|UserInfo
     */
    public function info($openid)
    {
        $params = $this->buildParams([
            'access_token' => $this->getAccessToken(),
            'openid' => $openid,
            'lang' => 'zh_CN'
        ]);
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?' . $params;
        $return = $this->jsonDecode($this->httpGet($url));
        $return = $this->checkReturn($return);
        if ($return) {
            $return = new UserInfo($return);
        }
        return $return;
    }


    /**
     * 批量获取用户基本信息,最多支持一次拉取100条
     * @param array $openids
     * @param bool $obj 是否返回UserInfo数组,默认false
     * @return array|bool|UserInfo[]
     */
    public function batchGetInfo($openids, $obj = false)
    {
        $data = ['user_list'=>[]];
        if (is_array($openids)) {
            foreach ($openids as $openid) {
                $data['user_list'][] = ['openid' => $openid];
            }
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode($data)));
        if (($return = $this->checkReturn($return)) && $obj) {
            $objs = [];
            foreach ($return['user_info_list'] as $info) {
                $objs[] = new UserInfo($info);
            }
            return $objs;
        }
        return $return;
    }

    /**
     * 获取用户列表
     * @param string $nextOpenid
     * @return array|bool
     */
    public function batchGet($nextOpenid = '')
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='. $this->getAccessToken() .'&next_openid=' . $nextOpenid;
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 获取黑名单的openid列表
     * @param string $beginOpenid 当$beginOpenid为空时,默认从开头拉取
     * @return array|bool
     */
    public function blackList($beginOpenid = '')
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/members/getblacklist?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'begin_openid' => $beginOpenid
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 拉黑用户
     * @param string|array $openids 一个openid或者openid的数组
     * @return array|bool
     */
    public function blackUsers($openids)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchblacklist?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'openid_list' => (array)$openids
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 取消拉黑用户
     * @param string|array $openids 一个openid或者openid的数组
     * @return array|bool
     */
    public function unBlackUsers($openids)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchunblacklist?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'openid_list' => (array)$openids
        ])));
        return $this->checkReturn($return);
    }
}
