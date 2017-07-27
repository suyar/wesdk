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
 * Class Music
 * @package wesdk\service\message
 */
class Music extends MsgBase
{
    /**
     * @var string 音乐标题
     */
    public $title;

    /**
     * @var string 音乐描述
     */
    public $description;

    /**
     * @var string 音乐链接
     */
    public $musicURL;

    /**
     * @var string 高质量音乐链接
     */
    public $HQMusicUrl;

    /**
     * @var string 缩略图媒体ID
     */
    public $thumbMediaId;
}
