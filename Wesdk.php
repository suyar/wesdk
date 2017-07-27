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

namespace wesdk;

/**
 * Class Wesdk
 * @package wesdk
 *
 * @property service\Card $card
 * @property service\Comment $comment
 * @property service\CSMessage $customMsg
 * @property service\CustomService $customServ
 * @property service\Datacube $datacube
 * @property service\Jssdk $jssdk
 * @property service\Mbuilder $mbuilder
 * @property service\Media $media
 * @property service\Menu $menu
 * @property service\Mpay $mpay
 * @property service\OAuth $oauth
 * @property service\Payment $payment
 * @property service\Poi $poi
 * @property service\PoiPro $poipro
 * @property service\Push $push
 * @property service\Scan $scan
 * @property service\Server $server
 * @property service\Shake $shake
 * @property service\Shop $shop
 * @property service\Template $template
 * @property service\User $user
 * @property service\UserTag $userTag
 * @property service\Util $util
 * @property service\Wifi $wifi
 */
class Wesdk
{
    /**
     * @var MpBase
     */
    private $mp;

    /**
     * @var array 类映射
     */
    private $map = [
        'card' => service\Card::class,
        'comment' => service\Comment::class,
        'customMsg' => service\CSMessage::class,
        'customServ' => service\CustomService::class,
        'datacube' => service\Datacube::class,
        'jssdk' => service\Jssdk::class,
        'mbuilder' => service\Mbuilder::class,
        'media' => service\Media::class,
        'menu' => service\Menu::class,
        'mpay' => service\Mpay::class,
        'oauth' => service\OAuth::class,
        'payment' => service\Payment::class,
        'poi' => service\Poi::class,
        'poipro' => service\PoiPro::class,
        'push' => service\Push::class,
        'scan' => service\Scan::class,
        'server' => service\Server::class,
        'shake' => service\Shake::class,
        'shop' => service\Shop::class,
        'template' => service\Template::class,
        'user' => service\User::class,
        'userTag' => service\UserTag::class,
        'util' => service\Util::class,
        'wifi' => service\Wifi::class,
    ];

    /**
     * @var service\BaseService[]
     */
    private $instance = [];

    /**
     * Wesdk constructor.
     * @param MpBase $mp
     */
    public function __construct(MpBase $mp)
    {
        $this->mp = $mp;
    }

    /**
     * 注册自动加载
     * @param string $className
     */
    public static function autoload($className)
    {
        if (class_exists($className) || strpos($className, 'wesdk') !== 0) {
            return;
        }
        require __DIR__ . str_replace('\\', DIRECTORY_SEPARATOR, substr($className, 5)) . '.php';
    }

    /**
     * 清空缓存
     */
    public function clearCache()
    {
        $dir = $this->mp->runtime() . DIRECTORY_SEPARATOR;
        foreach (glob($dir . '*.*') as $file) {
            unlink($file);
        }
    }

    /**
     * @param string $name
     * @return service\BaseService
     */
    public function __get($name)
    {
        if (!isset($this->instance[$name])) {
            $this->instance[$name] = new $this->map[$name]($this->mp);
        }
        return $this->instance[$name];
    }
}
spl_autoload_register([Wesdk::class, 'autoload']);