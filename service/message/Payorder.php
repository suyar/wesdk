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

namespace wesdk\service\message;

/**
 * Class Payorder
 * @package wesdk\service\message
 */
class Payorder extends MsgBase
{
    /**
     * @var string 非必填,设备号
     */
    public $device_info;

    /**
     * @var string 必填,商品描述
     */
    public $body;

    /**
     * @var string 非必填,商品详情
     */
    public $detail;

    /**
     * @var string 非必填,附加数据,在查询API和支付通知中原样返回,可作为自定义参数使用,请不要填写空值,包括0和'0'
     */
    public $attach;

    /**
     * @var string 必填,商户订单号,要求32个字符内
     */
    public $out_trade_no;

    /**
     * @var string 非必填,标价币种,默认人民币:CNY
     */
    public $fee_type;

    /**
     * @var string 必填,标价金额,单位为分
     */
    public $total_fee;

    /**
     * @var string 必填,终端IP,APP和网页支付提交用户端ip,Native支付填调用微信支付API的机器IP
     */
    public $spbill_create_ip;

    /**
     * @var string 非必填,交易起始时间,格式为yyyyMMddHHmmss
     */
    public $time_start;

    /**
     * @var string 非必填,交易结束时间,格式为yyyyMMddHHmmss
     */
    public $time_expire;

    /**
     * @var string 非必填,订单优惠标记,使用代金券或立减优惠功能时需要的参数
     */
    public $goods_tag;

    /**
     * @var string 必填,异步接收微信支付结果通知的回调地址,通知url必须为外网可访问的url,不能携带参数
     */
    public $notify_url;

    /**
     * @var string 必填,交易类型,取值如下:JSAPI,NATIVE,APP等
     */
    public $trade_type;

    /**
     * @var string 非必填,商品ID,trade_type=NATIVE时(即扫码支付),此参数必传
     */
    public $product_id;

    /**
     * @var string 非必填,指定支付方式,上传此参数no_credit可限制用户不能使用信用卡支付
     */
    public $limit_pay;

    /**
     * @var string 非必填,用户标识,trade_type=JSAPI时(即公众号支付),此参数必传
     */
    public $openid;

    /**
     * @var string 非必填,场景信息
     */
    public $scene_info;

    /**
     * @var string 非必填,刷卡支付必填,扫码支付授权码,设备读取用户微信中的条码或者二维码信息
     */
    public $auth_code;
}
