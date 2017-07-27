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
 * Class Wifi
 * @package wesdk\service
 */
class Wifi extends BaseService
{
    /**
     * 获取Wi-Fi门店列表
     * @param int $pageIndex 分页下标,默认从1开始
     * @param int $pageSize 每页的个数,默认10个,最大20个
     * @return array|bool
     */
    public function shopList($pageIndex, $pageSize = 10)
    {
        $url = 'https://api.weixin.qq.com/bizwifi/shop/list?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'pageindex' => $pageIndex,
            'pagesize' => $pageSize,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 查询门店Wi-Fi信息
     * @param int $shopId
     * @return array|bool
     */
    public function shopInfo($shopId)
    {
        $url = 'https://api.weixin.qq.com/bizwifi/shop/get?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['shop_id'=>$shopId])));
        return $this->checkReturn($return);
    }

    /**
     * 修改门店网络信息,选填设置为空值即可
     * @param int $shopId 门店ID
     * @param string $oldSsid 旧的ssid
     * @param string $ssid 新的ssid,当门店下是portal型设备时,ssid必填;当门店下是密码型设备时,ssid选填
     * @param string $password 无线网络设备的密码,当门店下是密码型设备时才可填写
     * @return array|bool
     */
    public function shopUpdate($shopId, $oldSsid, $ssid, $password = '')
    {
        $data = array_filter([
            'shop_id' => $shopId,
            'old_ssid' => $oldSsid,
            'ssid' => $ssid,
            'password' => $password,
        ]);
        $password && ($data['password'] = $password);
        $url = 'https://api.weixin.qq.com/bizwifi/shop/update?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode($data)));
        return $this->checkReturn($return);
    }

    /**
     * 清空门店网络及设备
     * @param int $shopId 门店ID
     * @return array|bool
     */
    public function shopClean($shopId)
    {
        $url = 'https://api.weixin.qq.com/bizwifi/shop/clean?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['shop_id'=>$shopId])));
        return $this->checkReturn($return);
    }

    /**
     * 添加密码型设备
     * @param int $shopId 门店ID
     * @param string $ssid 无线网络设备的ssid
     * @param string $password 无线网络设备的密码
     * @return array|bool
     */
    public function deviceAdd($shopId, $ssid, $password)
    {
        $url = 'https://api.weixin.qq.com/bizwifi/device/add?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'shop_id' => $shopId,
            'ssid' => $ssid,
            'password' => $password,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 添加portal型设备
     * @param int $shopId 门店ID
     * @param string $ssid 无线网络设备的ssid
     * @param bool $reset 是否重置secretkey,默认为否
     * @return array|bool
     */
    public function deviceAddPortal($shopId, $ssid, $reset = false)
    {
        $url = 'https://api.weixin.qq.com/bizwifi/apportal/register?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'shop_id' => $shopId,
            'ssid' => $ssid,
            'reset' => (bool)$reset,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 查询设备
     * @param int $pageIndex 分页下标,默认从1开始
     * @param int $pageSize 每页的个数,默认10个,最大20个
     * @param int $shopId 如果填写则,根据门店id查询
     * @return array|bool
     */
    public function deviceList($pageIndex = 1, $pageSize = 10, $shopId = 0)
    {
        $data = array_filter([
            'pageindex' => $pageIndex,
            'pagesize' => $pageSize,
        ]);
        $shopId && ($data['shop_id'] = $shopId);
        $url = 'https://api.weixin.qq.com/bizwifi/device/list?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode($data)));
        return $this->checkReturn($return);
    }

    /**
     * 删除设备
     * @param string $bssid 需要删除的无线网络设备无线mac地址,如:00:1f:7a:ad:5c:a8
     * @return array|bool
     */
    public function deviceDelete($bssid)
    {
        $url = 'https://api.weixin.qq.com/bizwifi/device/delete?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'bssid' => $bssid,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 获取物料二维码
     * @param int $shopId 门店ID
     * @param string $ssid 已添加到门店下的无线网络名称
     * @param int $imgId 0:纯二维码,1:二维码物料
     * @return array|bool
     */
    public function getQrcode($shopId, $ssid, $imgId)
    {
        $url = 'https://api.weixin.qq.com/bizwifi/qrcode/get?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'shop_id' => $shopId,
            'ssid' => $ssid,
            'img_id' => ($imgId ? 1 : 0),
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 设置商家主页
     * @param int $shopId 门店ID
     * @param string $url 如果填写了就是自定义链接,否则使用默认模板
     * @return array|bool
     */
    public function homePageSet($shopId, $url = '')
    {
        $data = ['shop_id'=>$shopId];
        if ($url) {
            $data['template_id'] = 1;
            $data['struct']['url'] = $url;
        } else {
            $data['template_id'] = 0;
        }
        $url = 'https://api.weixin.qq.com/bizwifi/homepage/set?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode($data)));
        return $this->checkReturn($return);
    }

    /**
     * 查询商家主页
     * @param int $shopId 门店ID
     * @return array|bool
     */
    public function homePageGet($shopId)
    {
        $url = 'https://api.weixin.qq.com/bizwifi/homepage/get?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'shop_id' => $shopId,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 设置微信首页欢迎语
     * @param int $shopId 门店ID
     * @param $barType 0.欢迎光临+公众号名称;1.欢迎光临+门店名称;2.已连接+公众号名称+WiFi;3.已连接+门店名称+Wi-Fi
     * @return array|bool
     */
    public function barSet($shopId, $barType)
    {
        $url = 'https://api.weixin.qq.com/bizwifi/bar/set?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'shop_id' => $shopId,
            'bar_type' => $barType,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 设置连网完成页
     * @param int $shopId 门店ID
     * @param string $pageUrl 连网完成页URL
     * @return array|bool
     */
    public function finishPageSet($shopId, $pageUrl)
    {
        $url = 'https://api.weixin.qq.com/bizwifi/finishpage/set?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'shop_id' => $shopId,
            'finishpage_url' => $pageUrl,
        ])));
        return $this->checkReturn($return);
    }

    /**
     *
     * @param string $beginDate 起始日期时间,格式yyyy-mm-dd
     * @param string $endDate 结束日期时间戳,格式yyyy-mm-dd,最长时间跨度为30天
     * @param int $shopId 按门店ID搜索,默认-1为总统计
     * @return array|bool
     */
    public function statistics($beginDate, $endDate, $shopId = -1)
    {
        $url = 'https://api.weixin.qq.com/bizwifi/statistics/list?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'begin_date' => $beginDate,
            'end_date' => $endDate,
            'shop_id' => $shopId,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 设置门店卡券投放信息
     * @param int $shopId 门店ID,可设置为0,表示所有门店
     * @param string $cardId 卡券ID
     * @param string $describe 卡券描述,不能超过18个字符
     * @param int $startTime 卡券投放开始时间戳
     * @param int $endTime 卡券投放结束时间戳
     * @return array|bool
     */
    public function couponSet($shopId, $cardId, $describe, $startTime, $endTime)
    {
        $url = 'https://api.weixin.qq.com/bizwifi/couponput/set?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'shop_id' => $shopId,
            'card_id' => $cardId,
            'card_describe' => $describe,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 查询门店卡券投放信息
     * @param int $shopId 门店ID,可设置为0,表示所有门店
     * @return array|bool
     */
    public function couponGet($shopId)
    {
        $url = 'https://api.weixin.qq.com/bizwifi/couponput/get?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'shop_id' => $shopId,
        ])));
        return $this->checkReturn($return);
    }
}
