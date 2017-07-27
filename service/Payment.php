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
 * Class Payment
 * @package wesdk\service
 */
class Payment extends BaseService
{
    /**
     * @var bool
     */
    private $result;

    /**
     * @var string
     */
    private $raw;

    /**
     * 统一下单接口
     * @param message\Payorder $data
     * @return array|bool
     */
    public function unifiedOrder(message\Payorder $data)
    {
        $data = array_filter((array)$data);
        $data['appid'] = $this->mp->appId;
        $data['mch_id'] = $this->mp->merchantId;
        $data['nonce_str'] = $this->nonceStr(32);
        $data['sign'] = $this->sign($data);
        $xml = $this->array2xml($data);

        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $stime = $this->getTime(true);
        $return = $this->checkXmlReturn($this->httpPost($url, $xml));
        $this->report($return, $url, $stime);
        return $return;
    }

    /**
     * 支付回调监听函数
     * @param callable $callback 接受两个回调函数,\wesdk\service\Message实例和\wesdk\service\Mbuilder实例
     */
    public function listen(callable $callback)
    {
        libxml_disable_entity_loader(true);
        $this->raw = file_get_contents('php://input');
        $obj = simplexml_load_string($this->raw, 'SimpleXMLElement', LIBXML_NOCDATA);
        $obj && ($obj = json_decode(json_encode($obj), true));
        if (isset($obj['sign']) && $obj['sign'] === $this->sign($obj)) {
            call_user_func($callback, new Messgae($obj), new Mbuilder());
        } else {
            $this->result = false;
        }
    }

    /**
     * 微信支付消息推送回调,如果是调试模式,会打印所有的请求,否则只打印校验成功的请求
     * @param string $responce 响应消息
     */
    private function noticeLog($responce)
    {
        if ($this->mp->debug || $this->result !== false) {
            $arr = [
                '[请求地址：' . $_SERVER['REQUEST_URI'] . ']',
                '[请求方式：' . $_SERVER['REQUEST_METHOD'] . ']',
            ];
            strtoupper($_SERVER['REQUEST_METHOD']) === 'POST' && array_push($arr, '[请求数据：' . $this->raw . ']');
            array_push($arr, '[响应数据：' . $responce . ']');
            $this->mp->logger(implode(PHP_EOL, $arr), 3);
        }
    }

    /**
     * 输出响应文本
     * @param bool $return 是否返回而不是直接输出
     * @return string
     */
    public function send($return = false)
    {
        $succ = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        $err = '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[签名错误]]></return_msg></xml>';
        $msg = $this->result === false ? $err : $succ;
        $this->noticeLog($msg);
        return $return ? $msg : exit($msg);
    }

    /**
     * 查询订单
     * @param string $orderSn 订单号
     * @param bool $isWx 是否为微信订单号,默认为否
     * @return array|bool
     */
    public function queryOrder($orderSn, $isWx = false)
    {
        $data = [
            'appid' => $this->mp->appId,
            'mch_id' => $this->mp->merchantId,
            'nonce_str' => $this->nonceStr(32),
            ($isWx ? 'transaction_id' : 'out_trade_no') => $orderSn,
        ];
        $data['sign'] = $this->sign($data);
        $url = 'https://api.mch.weixin.qq.com/pay/orderquery';
        $stime = $this->getTime(true);
        $return = $this->checkXmlReturn($this->httpPost($url, $this->array2xml($data)));
        $this->report($return, $url, $stime);
        return $return;
    }

    /**
     * 关闭订单
     * @param string $orderSn 商户订单号
     * @return array|bool
     */
    public function closeOrder($orderSn)
    {
        $data = [
            'appid' => $this->mp->appId,
            'mch_id' => $this->mp->merchantId,
            'nonce_str' => $this->nonceStr(32),
            'out_trade_no' => $orderSn,
        ];
        $data['sign'] = $this->sign($data);
        $url = 'https://api.mch.weixin.qq.com/pay/closeorder';
        $stime = $this->getTime(true);
        $return = $this->checkXmlReturn($this->httpPost($url, $this->array2xml($data)));
        $this->report($return, $url, $stime);
        return $return;
    }

    /**
     * 申请退款
     * @param message\Payrefund $data
     * @return array|bool
     */
    public function refund(message\Payrefund $data)
    {
        $data = array_filter((array)$data);
        $data['appid'] = $this->mp->appId;
        $data['mch_id'] = $this->mp->merchantId;
        $data['nonce_str'] = $this->nonceStr(32);
        $data['sign'] = $this->sign($data);
        $xml = $this->array2xml($data);

        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        $stime = $this->getTime(true);
        $return = $this->httpPost($url, $xml, [
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSLCERT => $this->mp->sslcert,
            CURLOPT_SSLKEY => $this->mp->sslkey,
            CURLOPT_CAINFO => $this->mp->cainfo,
        ]);
        $return = $this->checkXmlReturn($return);
        $this->report($return, $url, $stime);
        return $return;
    }

    /**
     * 查询退款
     * @param string $orderSn 单号
     * @param int $type 单号类型<br>
     * 1.商户订单号<br>
     * 2.微信订单号<br>
     * 3.商户退款单号<br>
     * 4.微信退款单号
     * @return array|bool
     */
    public function queryRefund($orderSn, $type)
    {
        $data = [
            'appid' => $this->mp->appId,
            'mch_id' => $this->mp->merchantId,
            'nonce_str' => $this->nonceStr(32),
        ];
        switch (intval($type)) {
            case 1:
                $data['out_trade_no'] = $orderSn;
                break;
            case 2:
                $data['transaction_id'] = $orderSn;
                break;
            case 3:
                $data['out_refund_no'] = $orderSn;
                break;
            case 4:
                $data['refund_id'] = $orderSn;
                break;
        }
        $data['sign'] = $this->sign($data);
        $url = 'https://api.mch.weixin.qq.com/pay/refundquery';
        $stime = $this->getTime(true);
        $return = $this->checkXmlReturn($this->httpPost($url, $this->array2xml($data)));
        $this->report($return, $url, $stime);
        return $return;
    }

    /**
     * 生成支付JS配置
     * @param string $prepayId 预支付交易会话标识
     * @param bool $json 是否返回json格式,默认false
     * @return array|bool
     */
    public function getJsConfig($prepayId, $json = false)
    {
        $data = [
            'appId' => $this->mp->appId,
            'timeStamp' => time(),
            'nonceStr' => $this->nonceStr(32),
            'package' => "prepay_id=$prepayId",
            'signType' => 'MD5',
        ];
        $data['paySign'] = $this->sign($data);
        return $json ? $this->jsonEncode($data) : $data;
    }

    /**
     * 转换短链接
     * @param string $longUrl 长链接
     * @return array|bool
     */
    public function shortUrl($longUrl)
    {
        $data = [
            'appid' => $this->mp->appId,
            'mch_id' => $this->mp->merchantId,
            'nonce_str' => $this->nonceStr(32),
            'long_url' => $longUrl,
        ];
        $data['sign'] = $this->sign($data);
        $url = 'https://api.mch.weixin.qq.com/tools/shorturl';
        $stime = $this->getTime(true);
        $return = $this->checkXmlReturn($this->httpPost($url, $this->array2xml($data)));
        $this->report($return, $url, $stime);
        return $return;
    }

    /**
     * 提交刷卡支付
     * @param message\Payorder $data
     * @return array|bool
     */
    public function microPay(message\Payorder $data)
    {
        $data = array_filter((array)$data);
        $data['appid'] = $this->mp->appId;
        $data['mch_id'] = $this->mp->merchantId;
        $data['nonce_str'] = $this->nonceStr(32);
        $data['sign'] = $this->sign($data);
        $xml = $this->array2xml($data);

        $url = 'https://api.mch.weixin.qq.com/pay/micropay';
        $stime = $this->getTime(true);
        $return = $this->checkXmlReturn($this->httpPost($url, $xml));
        $this->report($return, $url, $stime);
        return $return;
    }

    /**
     * 撤销订单(刷卡支付)
     * @param string $orderSn 订单号
     * @param bool $isWx 是否为微信订单号,默认为否
     * @return array|bool
     */
    public function reverse($orderSn, $isWx = false)
    {
        $data = [
            'appid' => $this->mp->appId,
            'mch_id' => $this->mp->merchantId,
            'nonce_str' => $this->nonceStr(32),
            ($isWx ? 'transaction_id' : 'out_trade_no') => $orderSn,
        ];
        $data['sign'] = $this->sign($data);
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/reverse';
        $stime = $this->getTime(true);
        $return = $this->httpPost($url, $this->array2xml($data), [
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSLCERT => $this->mp->sslcert,
            CURLOPT_SSLKEY => $this->mp->sslkey,
            CURLOPT_CAINFO => $this->mp->cainfo,
        ]);
        $return = $this->checkXmlReturn($return);
        $this->report($return, $url, $stime);
        return $return;
    }

    /**
     * 授权码查询openid
     * @param string $authCode 授权码
     * @return array|bool
     */
    public function authCodeToOpenid($authCode)
    {
        $data = [
            'appid' => $this->mp->appId,
            'mch_id' => $this->mp->merchantId,
            'auth_code' => $authCode,
            'nonce_str' => $this->nonceStr(32),
        ];
        $data['sign'] = $this->sign($data);
        $url = 'https://api.mch.weixin.qq.com/tools/authcodetoopenid';
        $stime = $this->getTime(true);
        $return = $this->checkXmlReturn($this->httpPost($url, $this->array2xml($data)));
        $this->report($return, $url, $stime);
        return $return;
    }

    /**
     * 下载对账单
     * @param string $billDate 下载对账单的日期,格式:20140603
     * @param string $billType
     * ALL:返回当日所有订单信息,默认值<br>
     * SUCCESS:返回当日成功支付的订单<br>
     * REFUND:返回当日退款订单<br>
     * RECHARGE_REFUND:返回当日充值退款订单
     * @param string $deviceInfo 微信支付分配的终端设备号
     * @return mixed
     */
    public function downloadBill($billDate, $billType = 'ALL', $deviceInfo = '')
    {
        $data = [
            'appid' => $this->mp->appId,
            'mch_id' => $this->mp->merchantId,
            'nonce_str' => $this->nonceStr(32),
            'bill_date' => $billDate,
            'bill_type' => $billType,
        ];
        $deviceInfo && ($data['device_info'] = $deviceInfo);
        $data['sign'] = $this->sign($data);
        $url = 'https://api.mch.weixin.qq.com/pay/downloadbill';
        $stime = $this->getTime(true);
        $return = $this->httpPost($url, $this->array2xml($data));
        if ($return && substr($return, 0 , 5) == '<xml>') {
            $return = $this->checkXmlReturn($return);
            $this->report($return, $url, $stime);
        }
        return $return;
    }

    /**
     * 用户上报
     * @param array $result 接口调用结果数组
     * @param string $url 接口URL
     * @param int $stime 接口调用开始时间
     */
    private function report($result, $url, $stime)
    {
        if (!$this->mp->reportLevel || !is_array($result)) {
            return;
        } elseif ($this->mp->reportLevel == 1 && isset($result['return_code']) && $result['return_code'] == 'SUCCESS' && isset($result['result_code']) && $result['result_code'] == 'SUCCESS') {
            return;
        }
        $data = [
            'appid' => $this->mp->appId,
            'mch_id' => $this->mp->merchantId,
            'nonce_str' => $this->nonceStr(32),
            'interface_url' => $url,
            'execute_time' => $this->getTime(true) - $stime,
            'user_ip' => $_SERVER['REMOTE_ADDR'],
            'time' => date('YmdHis'),
        ];
        isset($result['device_info']) && ($data['device_info'] = $result['device_info']);
        isset($result['return_code']) && ($data['return_code'] = $result['return_code']);
        isset($result['return_msg']) && ($data['return_msg'] = $result['return_msg']);
        isset($result['result_code']) && ($data['result_code'] = $result['result_code']);
        isset($result['err_code']) && ($data['err_code'] = $result['err_code']);
        isset($result['err_code_des']) && ($data['err_code_des'] = $result['err_code_des']);
        isset($result['out_trade_no']) && ($data['out_trade_no'] = $result['out_trade_no']);
        isset($result['device_info']) && ($data['device_info'] = $result['device_info']);
        $data['sign'] = $this->sign($data);
        $reportUrl = 'https://api.mch.weixin.qq.com/payitil/report';
        $this->httpPost($reportUrl, $this->array2xml($data));
    }
}
