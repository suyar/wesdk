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
 * Class Video
 * @package wesdk\service\message
 */
class Video extends MsgBase
{
    /**
     * @var string 视频消息的标题
     */
    public $title;

    /**
     * @var string 视频消息的描述
     */
    public $description;

    /**
     * @var string 视频媒体ID
     */
    public $mediaId;

    /**
     * @var string 视频封面媒体ID,客服发消息有用到
     */
    public $thumbMediaId;
}
