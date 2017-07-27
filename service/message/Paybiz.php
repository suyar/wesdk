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
 * Class Paybiz
 * @package wesdk\service\message
 */
class Paybiz extends MsgBase
{
    /**
     * @var string 非必填,微信支付分配的终端设备号
     */
    public $device_info;

    /**
     * @var string 必填,商户订单号,需保持唯一性(只能是字母或者数字,不能包含有符号)
     */
    public $partner_trade_no;

    /**
     * @var string 必填,用户openid
     */
    public $openid;

    /**
     * @var string 必填,校验用户姓名选项<br>
     * NO_CHECK:不校验真实姓名<br>
     * FORCE_CHECK:强校验真实姓名
     */
    public $check_name;

    /**
     * @var string 非必填,收款用户真实姓名<br>
     * 如果check_name设置为FORCE_CHECK,则必填用户真实姓名
     */
    public $re_user_name;

    /**
     * @var string 必填,企业付款金额,单位为分
     */
    public $amount;

    /**
     * @var string 必填,企业付款操作说明信息
     */
    public $desc;

    /**
     * @var string 必填,调用接口的机器Ip地址
     */
    public $spbill_create_ip;
}
