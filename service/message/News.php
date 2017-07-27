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
 * Class News
 * @package wesdk\service\message
 */
class News extends MsgBase
{
    /**
     * @var string 图文消息标题
     */
    public $title;

    /**
     * @var string 图文消息描述
     */
    public $description;

    /**
     * @var string 图片链接
     */
    public $picUrl;

    /**
     * @var string 点击图文消息跳转链接
     */
    public $url;
}
