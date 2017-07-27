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

namespace wesdk\service;

/**
 * Class UserInfo
 * @package wesdk\service
 *
 * @property string $openid 用户的唯一标识
 * @property string $unionid 只有在用户将公众号绑定到微信开放平台帐号后,才会出现该字段
 * @property string $nickname 用户昵称
 * @property string $sex 用户的性别,值为1时是男性,值为2时是女性,值为0时是未知
 * @property string $province 用户个人资料填写的省份
 * @property string $city 普通用户个人资料填写的城市
 * @property string $country 国家,如中国为CN
 * @property string $headimgurl 用户头像,最后一个数值代表正方形头像大小(有0/46/64/96/132数值可选,0代表640*640正方形头像)
 * @property string $privilege 用户特权信息,json数组,如微信沃卡用户为(chinaunicom)
 * @property int $subscribe 用户是否订阅该公众号标识,值为0时,代表此用户没有关注该公众号,拉取不到其余信息
 * @property string $language 用户的语言,简体中文为zh_CN
 * @property int $subscribe_time 用户关注时间,为时间戳;如果用户曾多次关注,则取最后关注时间
 * @property string $remark 公众号运营者对粉丝的备注,公众号运营者可在微信公众平台用户管理界面对粉丝添加备注
 * @property int $groupid 用户所在的分组ID(兼容旧的用户分组接口)
 * @property array $tagid_list 用户被打上的标签ID列表
 */
class UserInfo implements \ArrayAccess
{
    /**
     * @var array
     */
    private $info = [];

    /**
     * UserInfo constructor.
     * @param $info
     */
    public function __construct($info)
    {
        $this->info = $info;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->__isset($name) ? $this->info[$name] : null;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->info[$name]);
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->info[$name] = $value;
    }

    /**
     * @param $name
     */
    public function __unset($name)
    {
        if ($this->__isset($name)) {
            unset($this->info[$name]);
        }
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return $this->__isset($offset);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        $this->__unset($offset);
    }
}
