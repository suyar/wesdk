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
 * Class UserTag
 * @package wesdk\service
 */
class UserTag extends BaseService
{
    /**
     * 创建标签
     * @param string $name 标签名(30个字符以内)
     * @return array|bool
     */
    public function create($name)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/create?access_token' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'tag' => ['name' => $name]
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 获取公众号已创建的标签
     * @return array|bool
     */
    public function lists()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 编辑标签
     * @param int $id 标签ID
     * @param string $name 标签名(30个字符以内)
     * @return array|bool
     */
    public function update($id, $name)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/update?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'tag' => ['id' => $id, 'name' => $name]
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 删除标签
     * @param int $id 标签ID
     * @return array|bool
     */
    public function delete($id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/delete?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'tag' => ['id' => $id]
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 获取标签下粉丝列表
     * @param int $tagId 标签ID
     * @param string $nextOpenid 第一个拉取的OPENID,不填默认从头开始拉取
     * @return array|bool
     */
    public function usersOfTag($tagId, $nextOpenid = '')
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'tagid' => $tagId,
            'next_openid' => $nextOpenid
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 批量为用户打标签
     * @param array|string $openids 要打标签的openid或者openid数组
     * @param int $tagId 标签ID
     * @return array|bool
     */
    public function batchTagging($openids, $tagId)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'openid_list' => (array)$openids,
            'tagid' => $tagId
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 批量为用户取消标签
     * @param array|string $openids 要取消标签的openid或者openid数组
     * @param int $tagId 标签ID
     * @return array|bool
     */
    public function batchUntagging($openids, $tagId)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchuntagging?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'openid_list' => (array)$openids,
            'tagid' => $tagId
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 获取用户身上的标签列表
     * @param string $openid 用户的openid
     * @return array|bool
     */
    public function tagsOfUser($openid)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['openid'=>$openid])));
        return $this->checkReturn($return);
    }
}
