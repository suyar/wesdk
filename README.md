~~~
                         ____
 _      _____  _________/ / /__
| | /| / / _ \/ ___/ __  / //_/
| |/ |/ /  __(__  ) /_/ / ,<
|__/|__/\___/____/\__,_/_/|_|
~~~

INSTALL
-------
USE GIT
~~~
git clone git@github.com:carolkey/wesdk.git
~~~
~~~
require path/wesdk/Wesdk.php;
~~~
USE COMPOSER    
~~~
"require":{
    "carolkey/wesdk": "dev-master"
}
~~~

REQUIREMENTS
------------
> * php : >= 5.5.0
> * ext-openssl : *
> * ext-curl : *
> * ext-simplexml : *

USAGE
-----
最简单的使用方式：
~~~
$mp = new \wesdk\MpBase();
$mp->token = '11111';
$mp->appId = 'wxa40e95db13a91234';
$mp->appSecret = '1639fa0650c155k0b2abc38834a74321';
$mo->encodingAesKey = '7k2kdskafsdfdsfsdfsdfsdfdfsfsd';
$mp->merchantId = '123456';
$mp->merchantKey = '6039fa0650c15520b2abc38834654123';
$mp->reportLevel = 0;
$mp->cainfo = '/data/rootca.pem';
$mp->sslcert = '/data/apiclient_cert.pem';
$mp->sslkey = '/data/apiclient_key.pem';
$mp->debug = true;
 
$wesdk = new \wesdk\Wesdk($mp);
//使用素材管理接口
$media = $wesdk->media;
$media->...
~~~
如果有需要自定义缓存、日志打印、自定义运行时目录：
~~~
class Mp extends \wesdk\MpBase
{
    public function __construct()
    {
        $this->token = '11111';
        $this->appId = 'wxa40e95db13a91234';
        $this->appSecret = '1639fa0650c155k0b2abc38834a74321';
        $this->encodingAesKey = '7k2kdskafsdfdsfsdfsdfsdfdfsfsd';
        $this->merchantId = '123456';
        $this->merchantKey = '6039fa0650c15520b2abc38834654123';
        $this->reportLevel = 0;
        $this->cainfo = '/data/rootca.pem';
        $this->sslcert = '/data/apiclient_cert.pem';
        $this->sslkey = '/data/apiclient_key.pem';
        $this->debug = true;
    }
    
    public function setCache($key, $value)
    {
        // TODO: 自定义设置你的缓存
    }
    
    public function getCache($key)
    {
        // TODO: 自定义读取你的缓存
    }
    
    public function logger($str, $type)
    {
        // TODO: 自定义写入你的日志
    }
}
 
$wesdk = new \wesdk\Wesdk(new Mp());
//使用素材管理接口
$media = $wesdk->media;
$media->...
~~~
具体参数请看文档

DOCUMENTATION
-------------
_todo_

FEATURES
--------
* 代码库0依赖。
* 代码遵循PSR-2，PSR-4规范。
* 支持composer加载 + 内置自动加载器。
* 支持高并发的TOKEN刷新机制，不会出现高并发下API次数耗尽。
* 清晰明了的使用方式。
* 对常用的消息进行封装，IDE代码提示，没有json和xml烦恼。
* 支持自定义缓存、日志、自定义运行时目录，简便的API错误获取方式，助力你快速调试和开发。

LICENCE
-------
[MIT](https://opensource.org/licenses/MIT)

FEEDBACK
--------
* Issue：[Lying](https://github.com/carolkey/wesdk/issues)
* QQ：[296399959](http://wpa.qq.com/msgrd?v=3&uin=296399959&site=qq&menu=yes)
* MAIL：<carolkey@wesdk.org>
