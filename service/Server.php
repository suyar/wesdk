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
 * Class Server
 * @package wesdk\service
 */
class Server extends BaseService
{
    /**
     * @var Messgae
     */
    private $message;

    /**
     * @var string|message\Text|message\Image|message\Voice|message\Video|message\Music|message\News|message\News[]|message\Transfer|message\Raw
     */
    private $response;

    /**
     * @var bool
     */
    private $encryption;

    /**
     * @var Encryptor
     */
    private $encryptor;

    /**
     * @var string
     */
    private $raw;

    /**
     * @var bool
     */
    private $result;

    /**
     * Server constructor.
     * @param \wesdk\MpBase $mp
     */
    public function __construct(\wesdk\MpBase $mp)
    {
        parent::__construct($mp);
        $this->encryptor = new Encryptor($this->mp->token, $this->mp->appId, $this->mp->encodingAesKey);
    }

    /**
     * 校验TOKEN
     * @return string 正确返回true,错误返回false
     */
    private function validateToken()
    {
        return $this->encryptor->validateToken(
            $this->query('signature'),
            $this->query('timestamp'),
            $this->query('nonce')
        );
    }

    /**
     * 解密消息
     * @param string $ciphertext 密文
     * @return string 解密后的XML明文
     */
    private function decrypt($ciphertext)
    {
        return $this->encryptor->decryptMsg(
            $ciphertext,
            $this->query('msg_signature'),
            $this->query('timestamp'),
            $this->query('nonce')
        );
    }

    /**
     * 加密消息(根据公众号设置的消息模式是否加密)
     * @param string $text XML明文
     * @return string 返回加密后的密文
     */
    private function encrypt($text)
    {
        return $this->encryption ? $this->encryptor->encryptMsg(
            $text,
            $this->query('timestamp'),
            $this->query('nonce')
        ) : $text;
    }

    /**
     * 解析用户响应的内容
     */
    private function resoveRes()
    {
        if (is_array($this->response) && $this->response) {
            $obj = current($this->response);
            if (is_object($obj) && $obj instanceof message\News) {
                $class = get_class($obj);
            }
        } elseif (is_object($this->response) && $this->response instanceof message\MsgBase) {
            $class = get_class($this->response);
        } elseif (is_string($this->response) && $this->response) {
            $this->response = new message\Text(['content'=>$this->response]);
            $class = message\Text::class;
        }
        $this->response = isset($class) ? $this->buildXml(strtolower(substr($class, strrpos($class, '\\') + 1))) : '';
    }

    /**
     * 返回编译后的XML
     * @param string $type 消息类型
     * @return string
     */
    private function buildXml($type)
    {
        switch ($type) {
            case 'text':
                return $this->array2xml([
                    'ToUserName' => $this->message->FromUserName,
                    'FromUserName' => $this->message->ToUserName,
                    'CreateTime' => time(),
                    'MsgType' => $type,
                    'Content' => $this->response->content,
                ]);
            case 'image':
                return $this->array2xml([
                    'ToUserName' => $this->message->FromUserName,
                    'FromUserName' => $this->message->ToUserName,
                    'CreateTime' => time(),
                    'MsgType' => $type,
                    'Image' => ['MediaId'=>$this->response->mediaId],
                ]);
            case 'voice':
                return $this->array2xml([
                    'ToUserName' => $this->message->FromUserName,
                    'FromUserName' => $this->message->ToUserName,
                    'CreateTime' => time(),
                    'MsgType' => $type,
                    'Voice' => ['MediaId'=>$this->response->mediaId],
                ]);
            case 'video':
                return $this->array2xml([
                    'ToUserName' => $this->message->FromUserName,
                    'FromUserName' => $this->message->ToUserName,
                    'CreateTime' => time(),
                    'MsgType' => $type,
                    'Video' => [
                        'MediaId' => $this->response->mediaId,
                        'Title' => $this->response->title,
                        'Description' => $this->response->description,
                    ],
                ]);
            case 'music':
                return $this->array2xml([
                    'ToUserName' => $this->message->FromUserName,
                    'FromUserName' => $this->message->ToUserName,
                    'CreateTime' => time(),
                    'MsgType' => $type,
                    'Music' => [
                        'Title' => $this->response->title,
                        'Description' => $this->response->description,
                        'MusicUrl' => $this->response->musicURL,
                        'HQMusicUrl' => $this->response->HQMusicUrl,
                        'ThumbMediaId' => $this->response->thumbMediaId,
                    ],
                ]);
            case 'news':
                $data = [
                    'ToUserName' => $this->message->FromUserName,
                    'FromUserName' => $this->message->ToUserName,
                    'CreateTime' => time(),
                    'MsgType' => $type,
                    'ArticleCount' => 0,
                    'Articles' => ['item'=>[]],
                ];
                if (is_array($this->response)) {
                    foreach ($this->response as $item) {
                        if (is_object($item) && $item instanceof message\News) {
                            $data['ArticleCount']++;
                            $data['Articles']['item'][] = [
                                'Title' => $item->title,
                                'Description' => $item->description,
                                'PicUrl' => $item->picUrl,
                                'Url' => $item->url,
                            ];
                        }
                    }
                } elseif ($this->response instanceof message\News) {
                    $data['ArticleCount'] = 1;
                    $data['Articles']['item'][] = [
                        'Title' => $this->response->title,
                        'Description' => $this->response->description,
                        'PicUrl' => $this->response->picUrl,
                        'Url' => $this->response->url,
                    ];
                }
                return $this->array2xml($data);
            case 'transfer':
                $data = [
                    'ToUserName' => $this->message->FromUserName,
                    'FromUserName' => $this->message->ToUserName,
                    'CreateTime' => time(),
                    'MsgType' => 'transfer_customer_service',
                ];
                if ($this->response->account) {
                    $data['TransInfo'] = ['KfAccount'=>$this->response->account];
                }
                return $this->array2xml($data);
            case 'raw':
                return $this->response->content;
            default:
                return '';
        }
    }

    /**
     * 监听微信推送的消息
     * @param callable $callback 接受两个回调函数,\wesdk\service\Message实例和\wesdk\service\Mbuilder实例
     */
    public function listen(callable $callback)
    {
        $this->raw = file_get_contents('php://input');
        if ($this->validateToken()) {
            libxml_disable_entity_loader(true);
            $obj = simplexml_load_string($this->raw, 'SimpleXMLElement', LIBXML_NOCDATA);
            if (isset($obj->Encrypt)) {
                $this->encryption = true;
                $obj = simplexml_load_string($this->decrypt($obj->Encrypt), 'SimpleXMLElement', LIBXML_NOCDATA);
            }
            $this->message = new Messgae(json_decode(json_encode($obj), true));
            $this->response = call_user_func($callback, $this->message, new Mbuilder());
        } else {
            $this->result = false;
        }
    }

    /**
     * 输出响应文本
     * @param bool $return 是否返回而不是直接输出
     * @return string|null 当$return为true的时候返回字符串
     */
    public function send($return = false)
    {
        $this->resoveRes();
        if ($echostr = $this->query('echostr')) {
            $this->response = $echostr;
        } elseif (empty($this->response)) {
            $this->response = 'success';
        } else {
            $this->response = $this->encrypt($this->response);
        }
        $this->serverLog();
        return $return ? $this->response : exit($this->response);
    }

    /**
     * 微信消息推送回调,如果是调试模式,会打印所有的请求,否则只打印校验成功的请求
     */
    private function serverLog()
    {
        if ($this->mp->debug || $this->result !== false) {
            $arr = [
                '[请求地址：' . $_SERVER['REQUEST_URI'] . ']',
                '[请求方式：' . $_SERVER['REQUEST_METHOD'] . ']',
            ];
            strtoupper($_SERVER['REQUEST_METHOD']) === 'POST' && array_push($arr, '[请求数据：' . $this->raw . ']');
            array_push($arr, '[响应数据：' . $this->response . ']');
            $this->mp->logger(implode(PHP_EOL, $arr), 2);
        }
    }
}
