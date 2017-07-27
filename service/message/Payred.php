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
 * Class Payred
 * @package wesdk\service\message
 */
class Payred extends MsgBase
{
    /**
     * @var string 必填,商户订单号<br>
     * 单号必须唯一,组成:mch_id+yyyymmdd+10位一天内不能重复的数字
     */
    public $mch_billno;

    /**
     * @var string 必填,红包发送者名称,如:天虹百货
     */
    public $send_name;

    /**
     * @var string 非必填,红包预下单有效,默认为NORMAL<br>
     * NORMAL:普通红包<br>
     * GROUP:裂变红包
     */
    public $hb_type;

    /**
     * @var string 必填,接受红包用户的openid
     */
    public $re_openid;

    /**
     * @var string 必填,付款金额,单位分
     */
    public $total_amount;

    /**
     * @var string 必填,红包发放总人数<br>
     * 普通红包不必填写<br>
     * 裂变红包必须填写
     */
    public $total_num;

    /**
     * @var string 必填,红包祝福语
     */
    public $wishing;

    /**
     * @var string 必填,调用接口的机器Ip地址
     */
    public $client_ip;

    /**
     * @var string 必填,活动名称
     */
    public $act_name;

    /**
     * @var string 必填,备注信息
     */
    public $remark;

    /**
     * @var string 非必填,发放红包使用场景,红包金额大于200时必传
     */
    public $scene_id;

    /**
     * @var string 非必填,活动信息
     */
    public $risk_info;

    /**
     * @var string 非必填,资金授权商户号,服务商替特约商户发放时使用
     */
    public $consume_mch_id;

    /**
     * @var string 非必填,风控设置,红包预下单默认为NORMAL<br>
     * NORMAL:正常情况<br>
     * IGN_FREQ_LMT:忽略防刷限制,强制发放<br>
     * IGN_DAY_LMT:忽略单用户日限额限制,强制发放<br>
     * IGN_FREQ_DAY_LMT:忽略防刷和单用户日限额限制,强制发放
     */
    public $risk_cntl;
}
