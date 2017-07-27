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
 * Class CustomService
 * @package wesdk\service
 */
class CustomService extends BaseService
{
    /**
     * 获取所有客服列表
     * @return array|bool
     */
    public function lists()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 获取在线客服列表
     * @return array|bool
     */
    public function onlines()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/customservice/getonlinekflist?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }
    
    /**
     * 添加客服帐号
     * @param string $account 完整客服帐号
     * @param string $nickname 客服昵称
     * @return array|bool
     */
    public function create($account, $nickname)
    {
        $url = 'https://api.weixin.qq.com/customservice/kfaccount/add?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'kf_account' => $account,
            'nickname' => $nickname
        ])));
        return $this->checkReturn($return);
    }
    
    /**
     * 邀请绑定客服帐号
     * @param string $account 完整客服帐号
     * @param string $inviteWx 接收绑定邀请的客服微信号
     * @return array|bool
     */
    public function invite($account, $inviteWx)
    {
        $url = 'https://api.weixin.qq.com/customservice/kfaccount/inviteworker?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'kf_account' => $account,
            'invite_wx' => $inviteWx
        ])));
        return $this->checkReturn($return);
    }
    
    /**
     * 更新客服信息
     * @param string $account 完整客服帐号
     * @param string $nickname 客服昵称
     * @return array|bool
     */
    public function update($account, $nickname)
    {
        $url = 'https://api.weixin.qq.com/customservice/kfaccount/update?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'kf_account' => $account,
            'nickname' => $nickname
        ])));
        return $this->checkReturn($return);
    }
    
    /**
     * 上传客服头像
     * @param string $account 完整客服帐号
     * @param \CURLFile $avatar 上传的图片
     * @return array|bool
     */
    public function avatar($account, \CURLFile $avatar)
    {
        $url = 'https://api.weixin.qq.com/customservice/kfaccount/uploadheadimg?access_token=' . $this->getAccessToken() . '&kf_account=' . $account;
        $return = $this->jsonDecode($this->httpPost($url, ['media'=>$avatar]));
        return $this->checkReturn($return);
    }
    
    /**
     * 删除客服帐号
     * @param string $account 完整客服帐号
     * @return array|bool
     */
    public function delete($account)
    {
        $url = 'https://api.weixin.qq.com/customservice/kfaccount/del?access_token=' . $this->getAccessToken() . '&kf_account=' . $account;
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }
    
    /**
     * 创建会话
     * @param string $account 完整客服帐号
     * @param string $openId 粉丝的openid
     * @return array|bool
     */
    public function sessionCreate($account, $openId)
    {
        $url = 'https://api.weixin.qq.com/customservice/kfsession/create?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'kf_account' => $account,
            'openid' => $openId
        ])));
        return $this->checkReturn($return);
    }
    
    /**
     * 关闭会话
     * @param string $account 完整客服帐号
     * @param string $openId 粉丝的openid
     * @return array|bool
     */
    public function sessionClose($account, $openId)
    {
        $url = 'https://api.weixin.qq.com/customservice/kfsession/close?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'kf_account' => $account,
            'openid' => $openId
        ])));
        return $this->checkReturn($return);
    }
    
    /**
     * 获取客户会话状态
     * @param string $openId 粉丝的openid
     * @return array|bool
     */
    public function sessionGet($openId)
    {
        $url = 'https://api.weixin.qq.com/customservice/kfsession/getsession?access_token=' . $this->getAccessToken() . '&openid=' . $openId;
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }
    
    /**
     * 获取客服会话列表
     * @param string $account 完整客服帐号
     * @return array|bool
     */
    public function sessionList($account)
    {
        $url = 'https://api.weixin.qq.com/customservice/kfsession/getsessionlist?access_token=' . $this->getAccessToken() . '&kf_account=' . $account;
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }
    
    /**
     * 获取未接入会话列表
     * @return array|bool
     */
    public function sessionWait()
    {
        $url = 'https://api.weixin.qq.com/customservice/kfsession/getwaitcase?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 获取聊天记录
     * @param int $startTime 起始时间,unix时间戳
     * @param int $endTime 结束时间,unix时间戳,每次查询时段不能超过24小时
     * @param int $msgid 消息id顺序从小到大,从1开始
     * @param int $num 每次获取条数,最多10000条
     * @return array|bool
     */
    public function record($startTime, $endTime, $msgid, $num)
    {
        $url = 'https://api.weixin.qq.com/customservice/msgrecord/getmsglist?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'starttime' => $startTime,
            'endtime' => $endTime,
            'msgid' => $msgid,
            'number' => $num
        ])));
        return $this->checkReturn($return);
    }
}
