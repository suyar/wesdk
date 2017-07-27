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
 * Class Tpl
 * @package wesdk\service\message
 */
class Tpl extends MsgBase
{
    /**
     * @var string 接收者openid
     */
    public $touser;

    /**
     * @var string 模板ID
     */
    public $templateId;

    /**
     * @var string 模板跳转链接
     */
    public $url;

    /**
     * @var array 跳小程序所需数据,不需跳小程序可不用传该数据,['appid'=>'','pagepath'=>'']
     */
    public $miniprogram;

    /**
     * @var array 模板数据['keynote1'=>['value'=>'1元', 'color'=>'#173177']]
     */
    public $data;
}
