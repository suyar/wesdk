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
 * Class Menu
 * @package wesdk\service\message
 */
class Menu extends MsgBase
{
    /**
     * @var string 菜单标题
     */
    public $name;

    /**
     * @var string 菜单的响应动作类型
     */
    public $type;

    /**
     * @var string 菜单KEY值
     */
    public $key;

    /**
     * @var string 网页链接
     */
    public $url;

    /**
     * @var string 调用新增永久素材接口返回的合法media_id
     */
    public $mediaId;

    /**
     * @var string 小程序的appid
     */
    public $appid;

    /**
     * @var string 小程序的页面路径
     */
    public $pagePath;

    /**
     * @var Menu|Menu[] 二级菜单数组,个数应为1~5个
     */
    public $subButton;

    /**
     * @var string 携带参数[key]
     */
    public $type_click = 'click';

    /**
     * @var string 携带参数[url]
     */
    public $type_view = 'view';

    /**
     * @var string 携带参数[key]
     */
    public $type_scancodePush = 'scancode_push';

    /**
     * @var string 携带参数[key]
     */
    public $type_scancodeWaitmsg = 'scancode_waitmsg';

    /**
     * @var string 携带参数[key]
     */
    public $type_picSysphoto = 'pic_sysphoto';

    /**
     * @var string 携带参数[key]
     */
    public $type_picPhotoOrAlbum = 'pic_photo_or_album';

    /**
     * @var string 携带参数[key]
     */
    public $type_picWeixin = 'pic_weixin';

    /**
     * @var string 携带参数[key]
     */
    public $type_locationSelect = 'location_select';

    /**
     * @var string 携带参数[media_id]
     */
    public $type_mediaId = 'media_id';

    /**
     * @var string 携带参数[media_id]
     */
    public $type_viewLimited = 'view_limited';

    /**
     * @var string 携带参数[url,appid,pagepath]
     */
    public $type_miniprogram = 'miniprogram';
}
