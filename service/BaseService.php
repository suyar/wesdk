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
 * Class BaseService
 * @package wesdk\service
 */
class BaseService
{
    /**
     * @var \wesdk\MpBase
     */
    protected $mp;

    /**
     * @var int
     */
    private $errcode;

    /**
     * @var string
     */
    private $errmsg;

    /**
     * BaseService constructor.
     * @param \wesdk\MpBase $mp
     */
    public function __construct(\wesdk\MpBase $mp)
    {
        $this->mp = $mp;
    }

    /**
     * 获取公共accessToken
     * @return bool|string
     */
    final protected function getAccessToken()
    {
        return $this->getToken('accesstoken', [$this, 'refreshAccessToken']);
    }

    /**
     * 刷新公共accessToken
     * @return bool|string
     */
    private function refreshAccessToken()
    {
        $query = $this->buildParams([
            'grant_type' => 'client_credential',
            'appid' => $this->mp->appId,
            'secret' => $this->mp->appSecret,
        ]);
        $url = 'https://api.weixin.qq.com/cgi-bin/token?' . $query;
        $return = $this->jsonDecode($this->httpGet($url));
        if ($return = $this->checkReturn($return)) {
            $cacheKey = $this->mp->appId . 'accesstoken';
            $this->setCache($cacheKey, [
                'expire' => time() + $return['expires_in'] - 200,
                'token' => $return['access_token'],
            ]);
            return $return['access_token'];
        }
        return $return;
    }

    /**
     * 获取GET参数
     * @param string $name GET参数名
     * @return mixed 成功返回GET参数值,失败返回null
     */
    final protected function query($name)
    {
        return isset($_GET[$name]) ? $_GET[$name] : null;
    }

    /**
     * 获取当前时间戳
     * @param bool $milli 是否返回13位时间戳
     * @return float|int
     */
    final protected function getTime($milli = false)
    {
        return $milli ? ceil(microtime(true) * 1000) : time();
    }

    /**
     * 数组转XML
     * @param array $data 要转换的数组
     * @param string $root 设置父元素,默认xml
     * @return string 返回XML
     */
    final protected function array2xml($data, $root = 'xml')
    {
        $str = '';
        foreach ($data as $key => $value) {
            $is_item = is_numeric($key);
            if ($is_item) {
                if (is_array($value)) {
                    $str .= $this->array2xml($value, $root);
                } else {
                    $str .= "<$root>" . (is_numeric($value) ? $value : "<![CDATA[$value]]>") . "</$root>";
                }
            } elseif (is_array($value)) {
                $str .= $this->array2xml($value, $key);
            } else {
                $str .= "<$key>" . (is_numeric($value) ? $value : "<![CDATA[$value]]>") . "</$key>";
            }
        }
        return isset($is_item) && $is_item ? $str : "<$root>$str</$root>";
    }

    /**
     * 格式化XML返回
     * @param string $return
     * @return array|bool
     */
    final protected function checkXmlReturn($return)
    {
        if ($return) {
            libxml_disable_entity_loader(true);
            $return = json_decode(json_encode(simplexml_load_string($return, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        }
        return $return;
    }

    /**
     * 计算支付签名
     * @param array $data 计算签名的数组,会忽略sign字段
     * @return string
     */
    final protected function sign($data)
    {
        ksort($data);
        $buff = [];
        foreach ($data as $k => $v) {
            if ($k != 'sign' && $v != '') {
                $buff[] = "$k=$v";
            }
        }
        $str = implode('&', $buff) . '&key=' . $this->mp->merchantKey;
        return strtoupper(md5($str));
    }

    /**
     * 生成随机字符串
     * @param int $length 字符串长度
     * @return string
     */
    final protected function nonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        for ($str = '', $len = strlen($chars) - 1, $i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, $len), 1);
        }
        return $str;
    }

    /**
     * 组建查询参数
     * @param array $params 一个关联数组
     * @return string 返回a=1&b=2形式的字符串,没有经过urlencode
     */
    final protected function buildParams($params)
    {
        $p = [];
        foreach ($params as $name => $value) {
            $p[] = "$name=$value";
        }
        return implode('&', $p);
    }

    /**
     * 返回当前请求的完整URL
     * @return string
     */
    final protected function url()
    {
        $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $scheme . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * JSON编码数据
     * @param mixed $value 要编码的数据
     * @return string 返回JSON字符串,失败返回false
     */
    final protected function jsonEncode($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 解码JSON数据
     * @param string $json JSON字符串
     * @return array|bool 解码失败返回false
     */
    final protected function jsonDecode($json)
    {
        return json_decode($json, true);
    }

    /**
     * HTTP/HTTPS请求
     * @param string $url 请求的URL
     * @param array $options 额外的CURL选项
     * @return mixed 成功返回获取到的数据,失败返回false
     */
    final protected function httpGet($url, $options = [])
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, $options + [
            CURLOPT_TIMEOUT => 5,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $res = curl_exec($ch);
        $res || $this->setError(curl_errno($ch), curl_error($ch));
        curl_close($ch);
        $this->apiLog($url, $res);
        return $res;
    }

    /**
     * @param string $url 请求的url
     * @param array|string $data 可以是urlencoded后的字符串,类似'para1=val1&para2=val2&...';<br>
     * 也可以使用一个以字段名为键,字段数据为值的数组;<br>
     * 上传文件应为数组形式,且文件应为CURLFile实例
     * @param array $options 额外的CURL选项
     * @return mixed 成功返回获取到的数据,失败返回false
     */
    final protected function httpPost($url, $data, $options = [])
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, $options + [
            CURLOPT_TIMEOUT => 5,
            CURLOPT_POST => true,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SAFE_UPLOAD => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $res = curl_exec($ch);
        $res || $this->setError(curl_errno($ch), curl_error($ch));
        curl_close($ch);
        $this->apiLog($url, $res, true, $data);
        return $res;
    }

    /**
     * 主动调用API记录日志,只有在debug模式下才会打印
     * @param string $url 请求的URL
     * @param mixed $res 请求的返回值
     * @param bool $isPost 是否为POST
     * @param mixed $data POST过去的数据
     */
    private function apiLog($url, $res, $isPost = false, $data = '')
    {
        if ($this->mp->debug) {
            $arr = [
                '[请求地址：' . $url . ']',
                '[请求方式：' . ($isPost ? 'POST' : 'GET') . ']',
            ];
            $isPost && array_push($arr, '[请求数据：' . (is_string($data) ? $data : $this->jsonEncode($data)) . ']');
            array_push($arr, '[请求结果：' . (is_string($res) ? ((strpos($res, '{') === 0 || strpos($res, '<xml>') === 0) ? $res : '资源文件') : (is_bool($res) ? ($res ? 'true' : 'false') : '')) . ']');
            $this->mp->logger(implode(PHP_EOL, $arr), 1);
        }
    }

    /**
     * 检测微信接口返回的数据是否正确
     * @param mixed $return 微信接口获取到的数据
     * @return bool|array 成功原样返回,错误返回false
     */
    final protected function checkReturn($return)
    {
        if (empty($return)) {
            $return = false;
        } elseif (isset($return['errcode']) && 0 != $return['errcode']) {
            $this->setError($return['errcode'], isset($return['errmsg']) ? $return['errmsg'] : 'Unknown');
            $return = false;
        } else {
            $this->setError();
        }
        return $return;
    }

    /**
     * 设置接口错误信息
     * @param int $errcode 错误码
     * @param string $errmsg 错误信息
     */
    final protected function setError($errcode = 0, $errmsg = 'ok')
    {
        $this->errcode = $errcode;
        $this->errmsg = $errmsg;
    }

    /**
     * 获取错误码
     * @return int
     */
    final public function getErrcode()
    {
        return $this->errcode;
    }

    /**
     * 获取错误信息
     * @return string
     */
    final public function getErrmsg()
    {
        return $this->errmsg;
    }

    /**
     * 设置缓存
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    final protected function setCache($key, $value)
    {
        return $this->mp->setCache($key, $value);
    }

    /**
     * 获取缓存
     * @param string $key
     * @return mixed
     */
    final protected function getCache($key)
    {
        return $this->mp->getCache($key);
    }

    /**
     * 执行锁定代码
     * @param string $name 锁名
     * @param int $type 锁类型<br>
     * LOCK_SH 共享锁<br>
     * LOCK_EX 独占锁<br>
     * LOCK_NB 非阻塞锁,用法[LOCK_EX|LOCK_NB]
     * @param callable $call 锁定的代码
     * @return mixed 返回执行结果,失败返回false(执行结果也有可能是false)
     */
    final protected function lock($name, $type, callable $call)
    {
        $lockFile = $this->mp->runtime() . DIRECTORY_SEPARATOR . md5($name) . '.lock';
        if ($fp = fopen($lockFile, 'w')) {
            if (flock($fp, $type)) {
                $res = call_user_func($call);
                flock($fp, LOCK_UN);
            }
            fclose($fp);
        }
        return isset($res) ? $res : false;
    }

    /**
     * 加锁获取token
     * @param string $id 锁ID
     * @param callable $call 要执行的代码
     * @return bool|string 成功返回token,失败返回false
     */
    final protected function getToken($id, callable $call)
    {
        $cacheKey = $this->mp->appId . $id;
        $cache = $this->getCache($cacheKey);
        if (isset($cache['expire'])) {
            if ($cache['expire'] >= time()) {
                $token = $cache['token'];
            } else {
                $_this = $this;
                $res = $this->lock($cacheKey, LOCK_EX|LOCK_NB, function () use ($_this, $cacheKey, $call) {
                    $cache = $_this->getCache($cacheKey);
                    return isset($cache['expire']) && $cache['expire'] > time() ? $cache['token'] : call_user_func($call);
                });
                $token = $res ?: $cache['token'];
            }
        } else {
            $_this = $this;
            $token = $this->lock($cacheKey, LOCK_EX, function () use ($_this, $cacheKey, $call) {
                $cache = $_this->getCache($cacheKey);
                return isset($cache['expire']) && $cache['expire'] > time() ? $cache['token'] : call_user_func($call);
            });
        }
        return $token;
    }
}
