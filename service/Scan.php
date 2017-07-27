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
 * Class Scan
 * @package wesdk\service
 */
class Scan extends BaseService
{
    /**
     * 获取商户信息
     * @return array|bool
     */
    public function getMerchantInfo()
    {
        $url = 'https://api.weixin.qq.com/scan/merchantinfo/get?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 创建商品
     * @param string|array $json
     * @return array|bool
     */
    public function createProduct($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/scan/product/create?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 提交审核/取消发布商品
     * @param string $keyStandard 商品编码标准
     * @param string $keyStr 商品编码内容
     * @param int $status 设置发布状态,1为提交审核,0为取消发布
     * @return array|bool
     */
    public function changeStatus($keyStandard, $keyStr, $status)
    {
        $url = 'https://api.weixin.qq.com/scan/product/modstatus?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'keystandard' => $keyStandard,
            'keystr' => $keyStr,
            'status' => ($status ? 'on' : 'off'),
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 设置测试人员白名单
     * @param string|array $openids
     * @param string|array $usernames
     * @return array|bool
     */
    public function setTestWhiteList($openids = '', $usernames = '')
    {
        $url = 'https://api.weixin.qq.com/scan/testwhitelist/set?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'openid' => $openids ? (array)$openids : [],
            'username' => $usernames ? (array)$usernames : [],
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 获取商品二维码
     * @param string $keyStandard 商品编码标准
     * @param string $keyStr 商品编码内容
     * @param int $qrcodeSize 二维码的尺寸(整型),数值代表边长像素数,不填写默认值为100
     * @param string $extinfo 由商户自定义传入,建议仅使用大小写字母,数字及-_().*这6个常用字符
     * @return array|bool
     */
    public function getQrcode($keyStandard, $keyStr, $qrcodeSize = 100, $extinfo = '')
    {
        $url = 'https://api.weixin.qq.com/scan/product/getqrcode?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'keystandard' => $keyStandard,
            'keystr' => $keyStr,
            'qrcode_size' => $qrcodeSize ? intval($qrcodeSize) : 100,
            'extinfo' => $extinfo,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 查询商品信息
     * @param string $keyStandard 商品编码标准
     * @param string $keyStr 商品编码内容
     * @return array|bool
     */
    public function getProduct($keyStandard, $keyStr)
    {
        $url = 'https://api.weixin.qq.com/scan/product/get?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'keystandard' => $keyStandard,
            'keystr' => $keyStr,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 批量查询商品信息
     * @param int $offset 查询的起始位置,从0开始,包含该起始位置
     * @param int $limit 批量查询的数量
     * @param string $status on为发布状态,off为未发布状态,check为审核中状态,reject为审核未通过状态,all为所有状态
     * @param string $keyStr 按部分编码内容拉取,类似关键词搜索
     * @return array|bool
     */
    public function getProductList($offset, $limit, $status, $keyStr = '')
    {
        $url = 'https://api.weixin.qq.com/scan/product/getlist?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'offset' => $offset,
            'limit' => $limit,
            'status' => $status,
            'keystr' => $keyStr,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 更新商品信息
     * @param string $json
     * @return array|bool
     */
    public function updateProduct($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/scan/product/update?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 清除商品信息
     * @param string $keyStandard 商品编码标准
     * @param string $keyStr 商品编码内容
     * @return array|bool
     */
    public function clearProduct($keyStandard, $keyStr)
    {
        $url = 'https://api.weixin.qq.com/scan/product/clear?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'keystandard' => $keyStandard,
            'keystr' => $keyStr,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 检查wxticket参数
     * @param string $ticket 请求URL中带上的wxticket参数
     * @return array|bool
     */
    public function checkScanTicket($ticket)
    {
        $url = 'https://api.weixin.qq.com/scan/scanticket/check?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['ticket' => $ticket])));
        return $this->checkReturn($return);
    }

    /**
     * 清除扫码记录
     * @param string $keyStandard 商品编码标准
     * @param string $keyStr 商品编码内容
     * @param string $extinfo 调用"获取商品二维码接口"时传入的extinfo
     * @return array|bool
     */
    public function clearScanHistory($keyStandard, $keyStr, $extinfo)
    {
        return false;
        $url = '微信文档中接口URL和校验ticket接口重复,此接口暂时不可用' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'keystandard' => $keyStandard,
            'keystr' => $keyStr,
            'extinfo' => $extinfo,
        ])));
        return $this->checkReturn($return);
    }
}
