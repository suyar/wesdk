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
 * Class MpBase
 * @package wesdk
 */
class MpBase
{
    /**
     * @var string AppId
     */
    public $appId;

    /**
     * @var string AppSecret
     */
    public $appSecret;

    /**
     * @var string Token
     */
    public $token;

    /**
     * @var string EncodingAESKey
     */
    public $encodingAesKey;

    /**
     * @var string 商户ID
     */
    public $merchantId;

    /**
     * @var string 商户API密钥
     */
    public $merchantKey;

    /**
     * @var int 用户上报等级,0不上报,1上报错误,2全量上报,默认0
     */
    public $reportLevel = 0;

    /**
     * @var string CA证书的绝对路径
     */
    public $cainfo;

    /**
     * @var string SSL证书绝对路径
     */
    public $sslcert;

    /**
     * @var string SSL证书密钥绝对路径
     */
    public $sslkey;

    /**
     * @var bool 是否为调试模式
     */
    public $debug = false;

    /**
     * 返回临时文件夹路径
     * @return string
     */
    public function runtime()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'tmp';
    }

    /**
     * 设置缓存,在file模式下,如果要自定义缓存,直接重写此方法即可
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function setCache($key, $value)
    {
        $cacheFile = $this->runtime() . DIRECTORY_SEPARATOR . md5($key) . '.cache';
        if ($fp = fopen($cacheFile, 'w')) {
            if (flock($fp, LOCK_EX)) {
                $res = fwrite($fp, serialize($value));
                flock($fp, LOCK_UN);
            }
            fclose($fp);
        }
        return isset($res) && $res;
    }

    /**
     * 获取缓存,在file模式下,如果要自定义缓存,直接重写此方法即可
     * @param string $key
     * @return mixed
     */
    public function getCache($key)
    {
        $cacheFile = $this->runtime() . DIRECTORY_SEPARATOR . md5($key) . '.cache';
        if (file_exists($cacheFile) && ($fp = fopen($cacheFile, 'r'))) {
            if (flock($fp, LOCK_SH)) {
                $res = fread($fp, filesize($cacheFile));
                flock($fp, LOCK_UN);
            }
            fclose($fp);
        }
        return isset($res) ? unserialize($res) : false;
    }

    /**
     * 用户自定义日志
     * @param string $str 日志内容
     * @param int $type 日志类型,1.API调用日志,2.微信主动推送日志,3.微信支付主动推送日志
     */
    public function logger($str, $type)
    {

    }
}
