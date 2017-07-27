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
 * Class Mpay
 * @package wesdk\service
 */
class Mpay extends BaseService
{
    /**
     * 返回商户ID
     * @return string
     */
    public function getMchId()
    {
        return $this->mp->merchantId;
    }

    /**
     * 发放代金券
     * @param string $stockId 代金券批次id
     * @param string $tradeNo 商户此次发放凭据号(格式:商户id+日期+流水号),商户侧需保持唯一性
     * @param string $openid 用户openid
     * @param string $deviceInfo 微信支付分配的终端设备号
     * @return array|bool
     */
    public function couponSend($stockId, $tradeNo, $openid, $deviceInfo = '')
    {
        $data = [
            'coupon_stock_id' => $stockId,
            'openid_count' => 1,
            'partner_trade_no' => $tradeNo,
            'openid' => $openid,
            'appid' => $this->mp->appId,
            'mch_id' => $this->mp->merchantId,
            'nonce_str' => $this->nonceStr(32),
        ];
        $deviceInfo && ($data['device_info'] = $deviceInfo);
        $data['sign'] = $this->sign($data);
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/send_coupon';
        $return = $this->httpPost($url, $this->array2xml($data), [
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSLCERT => $this->mp->sslcert,
            CURLOPT_SSLKEY => $this->mp->sslkey,
            CURLOPT_CAINFO => $this->mp->cainfo,
        ]);
        return $this->checkXmlReturn($return);
    }

    /**
     * 查询代金券批次
     * @param string $stockId 代金券批次id
     * @param string $deviceInfo 微信支付分配的终端设备号
     * @return array|bool
     */
    public function couponQueryStock($stockId, $deviceInfo = '')
    {
        $data = [
            'coupon_stock_id' => $stockId,
            'appid' => $this->mp->appId,
            'mch_id' => $this->mp->merchantId,
            'nonce_str' => $this->nonceStr(32),
        ];
        $deviceInfo && ($data['device_info'] = $deviceInfo);
        $data['sign'] = $this->sign($data);
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/query_coupon_stock';
        $return = $this->httpPost($url, $this->array2xml($data));
        return $this->checkXmlReturn($return);
    }

    /**
     * 查询代金券信息
     * @param string $couponId 	代金券id
     * @param string $openid 用户openid
     * @param string $stockId 代金劵对应的批次号
     * @param string $deviceInfo 微信支付分配的终端设备号
     * @return array|bool
     */
    public function couponQueryInfo($couponId, $openid, $stockId, $deviceInfo = '')
    {
        $data = [
            'coupon_id' => $couponId,
            'openid' => $openid,
            'appid' => $this->mp->appId,
            'mch_id' => $this->mp->merchantId,
            'stock_id' => $stockId,
            'nonce_str' => $this->nonceStr(32),
        ];
        $deviceInfo && ($data['device_info'] = $deviceInfo);
        $data['sign'] = $this->sign($data);
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/querycouponsinfo';
        $return = $this->httpPost($url, $this->array2xml($data));
        return $this->checkXmlReturn($return);
    }

    /**
     * 红包预下单接口
     * @param message\Payred $data
     * @return array|bool
     */
    public function redpackPreorder(message\Payred $data)
    {
        $data = [
            'nonce_str' => $this->nonceStr(32),
            'mch_billno' => $data->mch_billno,
            'mch_id' => $this->mp->merchantId,
            'wxappid' => $this->mp->appId,
            'send_name' => $data->send_name,
            'hb_type' => $data->hb_type ?: 'NORMAL',
            'total_amount' => $data->total_amount,
            'total_num' => (empty($data->hb_type) || $data->hb_type == 'NORMAL') ? 1 : $data->total_num,
            'amt_type' => 'ALL_RAND',
            'wishing' => $data->wishing,
            'act_name' => $data->act_name,
            'remark' => $data->remark,
            'auth_mchid' => '1000052601',
            'auth_appid' => 'wxbf42bd79c4391863',
            'risk_cntl' => $data->risk_cntl ?: 'NORMAL',
        ];
        $data['sign'] = $this->sign($data);
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/hbpreorder';
        $return = $this->httpPost($url, $this->array2xml($data), [
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSLCERT => $this->mp->sslcert,
            CURLOPT_SSLKEY => $this->mp->sslkey,
            CURLOPT_CAINFO => $this->mp->cainfo,
        ]);
        return $this->checkXmlReturn($return);
    }

    /**
     * 发放普通红包
     * @param message\Payred $data
     * @return array|bool
     */
    public function redpackNormal(message\Payred $data)
    {
        $data = [
            'nonce_str' => $this->nonceStr(32),
            'mch_billno' => $data->mch_billno,
            'mch_id' => $this->mp->merchantId,
            'wxappid' => $this->mp->appId,
            'send_name' => $data->send_name,
            're_openid' => $data->re_openid,
            'total_amount' => $data->total_amount,
            'total_num' => 1,
            'wishing' => $data->wishing,
            'client_ip' => $data->client_ip ?: $_SERVER['SERVER_ADDR'],
            'act_name' => $data->act_name,
            'remark' => $data->remark,
        ];
        $data->scene_id && ($data['scene_id'] = $data->scene_id);
        $data->risk_info && ($data['risk_info'] = $data->risk_info);
        $data->consume_mch_id && ($data['consume_mch_id'] = $data->consume_mch_id);
        $data['sign'] = $this->sign($data);
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $return = $this->httpPost($url, $this->array2xml($data), [
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSLCERT => $this->mp->sslcert,
            CURLOPT_SSLKEY => $this->mp->sslkey,
            CURLOPT_CAINFO => $this->mp->cainfo,
        ]);
        return $this->checkXmlReturn($return);
    }

    /**
     * 发放裂变红包
     * @param message\Payred $data
     * @return array|bool
     */
    public function redpackGroup(message\Payred $data)
    {
        $data = [
            'nonce_str' => $this->nonceStr(32),
            'mch_billno' => $data->mch_billno,
            'mch_id' => $this->mp->merchantId,
            'wxappid' => $this->mp->appId,
            'send_name' => $data->send_name,
            're_openid' => $data->re_openid,
            'total_amount' => $data->total_amount,
            'total_num' => $data->total_num,
            'amt_type' => 'ALL_RAND',
            'wishing' => $data->wishing,
            'act_name' => $data->act_name,
            'remark' => $data->remark,
        ];
        $data->scene_id && ($data['scene_id'] = $data->scene_id);
        $data->risk_info && ($data['risk_info'] = $data->risk_info);
        $data->consume_mch_id && ($data['consume_mch_id'] = $data->consume_mch_id);
        $data['sign'] = $this->sign($data);
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack';
        $return = $this->httpPost($url, $this->array2xml($data), [
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSLCERT => $this->mp->sslcert,
            CURLOPT_SSLKEY => $this->mp->sslkey,
            CURLOPT_CAINFO => $this->mp->cainfo,
        ]);
        return $this->checkXmlReturn($return);
    }

    /**
     * 查询红包记录,支持普通红包和裂变包
     * @param string $mchBillno 商户发放红包的商户订单号
     * @return array|bool
     */
    public function redpackQuery($mchBillno)
    {
        $data = [
            'nonce_str' => $this->nonceStr(32),
            'mch_billno' => $mchBillno,
            'mch_id' => $this->mp->merchantId,
            'appid' => $this->mp->appId,
            'bill_type' => 'MCHT',
        ];
        $data['sign'] = $this->sign($data);
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gethbinfo';
        $return = $this->httpPost($url, $this->array2xml($data), [
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSLCERT => $this->mp->sslcert,
            CURLOPT_SSLKEY => $this->mp->sslkey,
            CURLOPT_CAINFO => $this->mp->cainfo,
        ]);
        return $this->checkXmlReturn($return);
    }

    /**
     * 企业付款
     * @param message\Paybiz $data
     * @return array|bool
     */
    public function bizPay(message\Paybiz $data)
    {
        $data = [
            'mch_appid' => $this->mp->appId,
            'mchid' => $this->mp->merchantId,
            'nonce_str' => $this->nonceStr(32),
            'partner_trade_no' => $data->partner_trade_no,
            'openid' => $data->openid,
            'check_name' => $data->check_name,
            'amount' => $data->amount,
            'desc' => $data->desc,
            'spbill_create_ip' => $data->spbill_create_ip ?: $_SERVER['SERVER_ADDR'],
        ];
        $data->device_info && ($data['device_info'] = $data->device_info);
        $data->re_user_name && ($data['re_user_name'] = $data->re_user_name);
        $data['sign'] = $this->sign($data);
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $return = $this->httpPost($url, $this->array2xml($data), [
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSLCERT => $this->mp->sslcert,
            CURLOPT_SSLKEY => $this->mp->sslkey,
            CURLOPT_CAINFO => $this->mp->cainfo,
        ]);
        return $this->checkXmlReturn($return);
    }

    /**
     * 查询企业付款
     * @param string $tradeNo 商户调用企业付款API时使用的商户订单号
     * @return array|bool
     */
    public function bizQuery($tradeNo)
    {
        $data = [
            'nonce_str' => $this->nonceStr(32),
            'partner_trade_no' => $tradeNo,
            'mch_id' => $this->mp->merchantId,
            'appid' => $this->mp->appId,
        ];
        $data['sign'] = $this->sign($data);
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo';
        $return = $this->httpPost($url, $this->array2xml($data), [
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSLCERT => $this->mp->sslcert,
            CURLOPT_SSLKEY => $this->mp->sslkey,
            CURLOPT_CAINFO => $this->mp->cainfo,
        ]);
        return $this->checkXmlReturn($return);
    }
}
