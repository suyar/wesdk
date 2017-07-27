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
 * Class Matchrule
 * @package wesdk\service\message
 */
class Matchrule extends MsgBase
{
    /**
     * @var string 用户标签的id
     */
    public $tagId;

    /**
     * @var int 性别:1男2女,不填则不做匹配
     */
    public $sex;

    /**
     * @var string 国家信息
     */
    public $country;

    /**
     * @var string 省份信息
     */
    public $province;

    /**
     * @var string 城市信息
     */
    public $city;

    /**
     * @var string 客户端版本IOS(1),Android(2),Others(3),不填则不做匹配
     */
    public $clientPlatformType;

    /**
     * @var string 语言信息,参见<https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1455782296>
     */
    public $language;
}
