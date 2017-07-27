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
 * Class Menu
 * @package wesdk\service
 */
class Menu extends BaseService
{
    /**
     * 自定义菜单创建接口
     * @param string|message\Menu|message\Menu[]|message\Raw $data
     * @return array|bool
     */
    public function create($data)
    {
        if (is_array($data)) {
            $arr = ['button'=>[]];
            foreach ($data as $item) {
                if (is_object($item) && $item instanceof message\Menu) {
                    $arr['button'][] = $this->buildMenu($item);
                }
            }
            $json = $this->jsonEncode($arr);
        } elseif (is_object($data)) {
            if ($data instanceof message\Menu) {
                $arr = ['button'=>[$this->buildMenu($data)]];
                $json = $this->jsonEncode($arr);
            } elseif ($data instanceof message\Raw) {
                $json = $data->content;
            }
        } elseif (is_string($data)) {
            $json = $data;
        }
        if (isset($json)) {
            $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $this->getAccessToken();
            $return = $this->jsonDecode($this->httpPost($url, $json));
            return $this->checkReturn($return);
        }
        $this->setError(-3, '错误的消息类型');
        return false;
    }

    /**
     * 编译菜单
     * @param message\Menu $item
     * @return array
     */
    private function buildMenu(message\Menu $item)
    {
        $data = ['name'=>$item->name];
        if ($item->subButton) {
            if (is_array($item->subButton)) {
                foreach ($item->subButton as $subitem) {
                    if (is_object($subitem) && $subitem instanceof message\Menu) {
                        $data['sub_button'][] = $this->buildMenu($subitem);
                    }
                }
            } elseif (is_object($item->subButton) && $item->subButton instanceof message\Menu) {
                $data['sub_button'][] = $this->buildMenu($item->subButton);
            }
        } else {
            $data['type'] = $item->type;
            switch ($item->type) {
                case $item->type_click:
                    $data['key'] = $item->key;
                    break;
                case $item->type_view:
                    $data['url'] = $item->url;
                    break;
                case $item->type_scancodePush:
                    $data['key'] = $item->key;
                    break;
                case $item->type_scancodeWaitmsg:
                    $data['key'] = $item->key;
                    break;
                case $item->type_picSysphoto:
                    $data['key'] = $item->key;
                    break;
                case $item->type_picPhotoOrAlbum:
                    $data['key'] = $item->key;
                    break;
                case $item->type_picWeixin:
                    $data['key'] = $item->key;
                    break;
                case $item->type_locationSelect:
                    $data['key'] = $item->key;
                    break;
                case $item->type_mediaId:
                    $data['media_id'] = $item->mediaId;
                    break;
                case $item->type_viewLimited:
                    $data['media_id'] = $item->mediaId;
                    break;
                case $item->type_miniprogram:
                    $data['url'] = $item->url;
                    $data['appid'] = $item->appid;
                    $data['pagepath'] = $item->pagePath;
                    break;
                default:
                    break;
            }
        }
        return $data;
    }

    /**
     * 自定义菜单查询接口
     * @return array|bool
     */
    public function get()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 自定义菜单删除接口
     * @return array|bool
     */
    public function delete()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 创建个性化菜单
     * 如果$data为json字符串或者Raw对象,则$matchrule不生效
     * @param string|message\Menu|message\Menu[]|message\Raw $data 菜单信息
     * @param bool|message\Matchrule $matchrule 菜单匹配规则,$data为字符串或者Raw时放空即可
     * @return array|bool
     */
    public function createConditional($data, $matchrule = false)
    {
        if (is_array($data)) {
            $arr = ['button'=>[], 'matchrule'=>[]];
            foreach ($data as $item) {
                if (is_object($item) && $item instanceof message\Menu) {
                    $arr['button'][] = $this->buildMenu($item);
                }
            }
            $arr['matchrule'] = $this->buildMatchrule($matchrule);
            $json = $this->jsonEncode($arr);
        } elseif (is_object($data)) {
            if ($data instanceof message\Menu) {
                $arr = ['button'=>[$this->buildMenu($data)], 'matchrule'=>$this->buildMatchrule($matchrule)];
                $json = $this->jsonEncode($arr);
            } elseif ($data instanceof message\Raw) {
                $json = $data->content;
            }
        } elseif (is_string($data)) {
            $json = $data;
        }
        if (isset($json)) {
            $url = 'https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token=' . $this->getAccessToken();
            $return = $this->jsonDecode($this->httpPost($url, $json));
            return $this->checkReturn($return);
        }
        $this->setError(-3, '错误的消息类型');
        return false;
    }

    /**
     * 编译个性化菜单匹配规则
     * @param mixed $matchrule
     * @return array
     */
    private function buildMatchrule($matchrule)
    {
        if (is_object($matchrule) && $matchrule instanceof message\Matchrule) {
            $rule = [
                'tag_id' => $matchrule->tagId,
                'sex' => $matchrule->sex,
                'country' => $matchrule->country,
                'province' => $matchrule->province,
                'city' => $matchrule->city,
                'client_platform_type' => $matchrule->clientPlatformType,
                'language' => $matchrule->language,
            ];
        }
        return isset($rule) ? $rule : [];
    }

    /**
     * 删除个性化菜单
     * @param int $menuId 个性化菜单ID
     * @return array|bool
     */
    public function removeConditional($menuId)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/delconditional?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['menuid'=>$menuId])));
        return $this->checkReturn($return);
    }

    /**
     * 测试个性化菜单匹配结果
     * @param string $userId 可以是粉丝的OpenID,也可以是粉丝的微信号
     * @return array|bool
     */
    public function tryMatch($userId)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/trymatch?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['user_id'=>$userId])));
        return $this->checkReturn($return);
    }

    /**
     * 获取自定义菜单配置接口;
     * 本接口与自定义菜单查询接口的不同之处在于,本接口无论公众号的接口是如何设置的,都能查询到接口;
     * 而自定义菜单查询接口则仅能查询到使用API设置的菜单配置
     * @return array|bool
     */
    public function menuInfo()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 获取公众号的自动回复规则
     * @return array|bool
     */
    public function autoReplyInfo()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/get_current_autoreply_info?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }
}
