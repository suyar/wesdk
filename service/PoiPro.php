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
 * Class PoiPro
 * @package wesdk\service
 */
class PoiPro extends BaseService
{
    /**
     * 拉取门店小程序类目
     * @return array|bool
     */
    public function getMerchantCategory()
    {
        $url = 'https://api.weixin.qq.com/wxa/get_merchant_category?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 创建门店小程序
     * @param string|array $json
     * @return array|bool
     */
    public function create($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/wxa/apply_merchant?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 查询门店小程序审核结果
     * @return array|bool
     */
    public function getMerchantAuditInfo()
    {
        $url = 'https://api.weixin.qq.com/wxa/get_merchant_audit_info?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 修改门店小程序信息
     * @param string $headimgMediaid 门店头像的临时素材mediaid,如果不想改,参数传空值
     * @param string $intro 门店小程序的介绍,如果不想改,参数传空值
     * @return array|bool
     */
    public function modMerchant($headimgMediaid = '', $intro = '')
    {
        $url = 'https://api.weixin.qq.com/wxa/modify_merchant?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'headimg_mediaid' => $headimgMediaid,
            'intro' => $intro,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 从腾讯地图拉取省市区信息
     * @return array|bool
     */
    public function getDistrict()
    {
        $url = 'https://api.weixin.qq.com/wxa/get_district?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 在腾讯地图中搜索门店
     * @param int $districtid 对应拉取省市区信息接口中的id字段
     * @param string $keyword 搜索的关键词
     * @return array|bool
     */
    public function searchMapPoi($districtid, $keyword)
    {
        $url = 'https://api.weixin.qq.com/wxa/search_map_poi?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'districtid' => $districtid,
            'keyword' => $keyword,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 在腾讯地图中创建门店
     * @param string|array $json
     * @return array|bool
     */
    public function createMapPoi($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/wxa/create_map_poi?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 添加门店
     * @param string|array $json
     * @return array|bool
     */
    public function addStore($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/wxa/add_store?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 更新门店信息
     * @param string|array $json
     * @return array|bool
     */
    public function updateStore($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/wxa/update_store?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 获取单个门店信息
     * @param string $poiId 小程序门店id
     * @return array|bool
     */
    public function getStoreInfo($poiId)
    {
        $url = 'https://api.weixin.qq.com/wxa/get_store_info?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['poi_id'=>$poiId])));
        return $this->checkReturn($return);
    }

    /**
     * 获取门店信息列表
     * @param int $offset 获取门店列表的初始偏移位置,从0开始计数
     * @param int $limit 获取门店个数,最大不能超过50个
     * @return array|bool
     */
    public function getStoreList($offset, $limit)
    {
        $url = 'https://api.weixin.qq.com/wxa/get_store_list?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'offset' => $offset,
            'limit' => $limit,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 删除门店
     * @param string $poiId 小程序门店id
     * @return array|bool
     */
    public function delStore($poiId)
    {
        $url = 'https://api.weixin.qq.com/wxa/del_store?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['poi_id'=>$poiId])));
        return $this->checkReturn($return);
    }

    /**
     * 取门店小程序配置的卡券
     * @param string $poiId 小程序门店id
     * @return array|bool
     */
    public function getCard($poiId)
    {
        $url = 'https://api.weixin.qq.com/card/storewxa/get?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['poi_id'=>$poiId])));
        return $this->checkReturn($return);
    }

    /**
     * 设置门店小程序配置的卡券
     * @param string $poiId 小程序门店id
     * @param string $cardId 微信卡券id
     * @return array|bool
     */
    public function setCard($poiId, $cardId)
    {
        $url = 'https://api.weixin.qq.com/card/storewxa/set?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'poi_id' => $poiId,
            'card_id' => $cardId,
        ])));
        return $this->checkReturn($return);
    }
}
