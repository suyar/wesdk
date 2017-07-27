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
 * Class Article
 * @package wesdk\service\message
 */
class Article extends MsgBase
{
    /**
     * @var string 文章标题
     */
    public $title;

    /**
     * @var string 封面图片媒体ID,必须是永久mediaId
     */
    public $thumbMediaId;

    /**
     * @var string 文章作者
     */
    public $author;

    /**
     * @var string 文章摘要
     */
    public $digest;

    /**
     * @var int 是否显示封面,0不显示,1显示
     */
    public $showCoverPic;

    /**
     * @var string 文章内容
     */
    public $content;

    /**
     * @var string 文章的原文地址
     */
    public $contentSourceUrl;

    /**
     * @var int 是否打开评论,0不打开,1打开
     */
    public $needOpenComment;

    /**
     * @var int 是否粉丝才可评论,0所有人可评论,1粉丝才可评论
     */
    public $onlyFansCanComment;
}
