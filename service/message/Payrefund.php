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
 * Class Payrefund
 * @package wesdk\service\message
 */
class Payrefund extends MsgBase
{
    /**
     * @var string 非必填,微信生成的订单号,和商户订单号二选一
     */
    public $transaction_id;

    /**
     * @var string 非必填,商户订单号,和微信订单号二选一
     */
    public $out_trade_no;

    /**
     * @var string 必填,商户退款单号
     */
    public $out_refund_no;

    /**
     * @var int 必填,订单金额,单位为分
     */
    public $total_fee;

    /**
     * @var int 必填,退款金额
     */
    public $refund_fee;

    /**
     * @var string 非必填,币种,默认人民币:CNY
     */
    public $refund_fee_type;

    /**
     * @var string 非必填,退款原因
     */
    public $refund_desc;

    /**
     * @var string 非必填,退款资金来源,仅针对老资金流商户使用<br>
     * REFUND_SOURCE_UNSETTLED_FUNDS---未结算资金退款(默认使用未结算资金退款)<br>
     * REFUND_SOURCE_RECHARGE_FUNDS---可用余额退款
     */
    public $refund_account;
}
