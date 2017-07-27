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
 * Class Poi
 * @package wesdk\service
 */
class Poi extends BaseService
{
    /**
     * 创建门店
     * @param string|message\Poi|message\Raw $data
     * @return array|bool
     */
    public function create($data)
    {
        if (is_object($data)) {
            if ($data instanceof message\Poi) {
                $data->categories = (array)$data->categories;
                if (is_array($data->photo_list)) {
                    foreach ($data->photo_list as $k => $url) {
                        $data->photo_list[$k] = ['photo_url'=>$url];
                    }
                }
                $json = $this->jsonEncode(['business'=>['base_info'=>array_filter((array)$data)]]);
            } elseif ($data instanceof message\Raw) {
                $json = $data->content;
            }
        } elseif (is_string($data) && $data) {
            $json = $data;
        }
        if (isset($json)) {
            $url = 'https://api.weixin.qq.com/cgi-bin/poi/addpoi?access_token=' . $this->getAccessToken();
            $return = $this->jsonDecode($this->httpPost($url, $json));
            return $this->checkReturn($return);
        }
        $this->setError(-3, '错误的消息类型');
        return false;
    }

    /**
     * 查询门店信息
     * @param int $poiId
     * @return array|bool
     */
    public function getPoi($poiId)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/poi/getpoi?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['poi_id'=>$poiId])));
        return $this->checkReturn($return);
    }

    /**
     * 查询门店列表
     * @param int $begin 开始位置,0即为从第一条开始查询
     * @param int $limit 返回数据条数,最大允许50
     * @return array|bool
     */
    public function getPoiList($begin, $limit)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/poi/getpoilist?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'begin' => $begin,
            'limit' => $limit,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 修改门店服务信息
     * @param string|message\Poi|message\Raw $data
     * @return array|bool
     */
    public function update($data)
    {
        if (is_object($data)) {
            if ($data instanceof message\Poi) {
                if (is_array($data->photo_list)) {
                    foreach ($data->photo_list as $k => $url) {
                        $data->photo_list[$k] = ['photo_url'=>$url];
                    }
                }
                $json = $this->jsonEncode(['business'=>['base_info'=>array_filter((array)$data)]]);
            } elseif ($data instanceof message\Raw) {
                $json = $data->content;
            }
        } elseif (is_string($data) && $data) {
            $json = $data;
        }
        if (isset($json)) {
            $url = 'https://api.weixin.qq.com/cgi-bin/poi/updatepoi?access_token=' . $this->getAccessToken();
            $return = $this->jsonDecode($this->httpPost($url, $json));
            return $this->checkReturn($return);
        }
        $this->setError(-3, '错误的消息类型');
        return false;
    }

    /**
     * 删除门店
     * @param int $poiId
     * @return array|bool
     */
    public function delete($poiId)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/poi/delpoi?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['poi_id'=>$poiId])));
        return $this->checkReturn($return);
    }

    /**
     * 获取门店类目表
     * @return array|bool
     */
    public function getWxCategory()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/poi/getwxcategory?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }
}
