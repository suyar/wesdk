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
 * Class Encryptor
 * @package wesdk\service
 */
class Encryptor
{
    /**
     * @var string
     */
    private $token;
    
    /**
     * @var string
     */
    private $appId;
    
    /**
     * @var string
     */
    private $encodingAesKey;
    
    /**
     * @var string
     */
    private $iv;

    /**
     * Encryptor constructor.
     * @param string $token
     * @param string $appId
     * @param string $encodingAesKey
     */
    public function __construct($token, $appId, $encodingAesKey)
    {
        $this->token = $token;
        $this->appId = $appId;
        $this->encodingAesKey = base64_decode($encodingAesKey . '=');
        $this->iv = substr($this->encodingAesKey, 0, 16);
    }
    
    /**
     * 校验TOKEN
     * @param string $signature GET参数的signature
     * @param string $timestamp GET参数的timestamp
     * @param string $nonce GET参数的nonce
     * @return bool 正确返回true,错误返回false
     */
    public function validateToken($signature, $timestamp, $nonce)
    {
        return $signature === $this->hash([$this->token, $timestamp, $nonce]);
    }
    
    /**
     * 加密回复用户的消息
     * @param string $reply 待回复用户的明文消息,XML格式的字符串
     * @param string $timestamp GET参数的timestamp
     * @param string $nonce GET参数的nonce
     * @return string 返回加密后的可以直接回复的密文
     */
    public function encryptMsg($reply, $timestamp, $nonce)
    {
        $ciphertext = $this->encrypt($reply);
        $hash = $this->hash([$this->token, $timestamp, $nonce, $ciphertext]);
        $format = <<<REPLY
<xml>
<Encrypt><![CDATA[%s]]></Encrypt>
<MsgSignature><![CDATA[%s]]></MsgSignature>
<TimeStamp>%s</TimeStamp>
<Nonce><![CDATA[%s]]></Nonce>
</xml>
REPLY;
        return sprintf($format, $ciphertext, $hash, $timestamp, $nonce);
    }
    
    /**
     * 解密消息
     * @param string $ciphertext 密文
     * @param string $msgSignature GET参数的msg_timestamp
     * @param string $timestamp GET参数的timestamp
     * @param string $nonce GET参数的nonce
     * @return string|bool 成功返回XML明文,失败返回false
     */
    public function decryptMsg($ciphertext, $msgSignature, $timestamp, $nonce)
    {
        $hash = $this->hash([$this->token, $timestamp, $nonce, $ciphertext]);
        return $hash === $msgSignature ? $this->decrypt($ciphertext) : false;
    }
    
    /**
     * 对明文进行加密
     * @param string $text 需要加密的明文
     * @return string 加密后的数据
     */
    private function encrypt($text)
    {
        $random = $this->randomStr();
        $text = $this->pkcs7Encode($random . pack("N", strlen($text)) . $text . $this->appId);
        $ciphertext = openssl_encrypt($text, 'AES-256-CBC', $this->encodingAesKey, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $this->iv);
		return base64_encode($ciphertext);
    }
    
    /**
     * 解密密文
     * @param string $ciphertext 密文
     * @return string 成功返回XML明文,失败返回false
     */
    private function decrypt($ciphertext)
    {
        $data = base64_decode($ciphertext);
        $text = openssl_decrypt($data, 'AES-256-CBC', $this->encodingAesKey, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $this->iv);
        $res = $this->pkcs7Decode($text);
        $content = substr($res, 16);
        $xmlLen = unpack("N", substr($content, 0, 4))[1];
        return substr($content, $xmlLen + 4) === $this->appId ? substr($content, 4, $xmlLen) : false;
    }
    
    /**
     * 对需要加密的明文进行填充补位
     * @param string $text 需要进行填充补位操作的明文
     * @return string 补齐的明文字符串
     */
    private function pkcs7Encode($text)
    {
        $pad = 32 - (strlen($text) % 32);
        $pad = $pad === 0 ? 32 : $pad;
        for ($i = 0, $str = '', $chr = chr($pad); $i < $pad; $i++) {
            $str .= $chr;
        }
        return $text . $str;
    }
    
    /**
     * 对解密后的明文进行补位删除
     * @param string $text AES解密后的明文
     * @return string 删除填充补位后的明文
     */
    private function pkcs7Decode($text)
    {
        $pad = ord(substr($text, -1));
        $pad = ($pad < 1 || $pad > 32) ? 0 : $pad;
        return substr($text, 0, (strlen($text) - $pad));
    }
    
    /**
     * 计算消息体签名
     * @param array $arr 要计算签名的数组
     * @return string 返回签名
     */
    private function hash($arr)
    {
        sort($arr, SORT_STRING);
        return sha1(implode($arr));
    }
    
    /**
     * 随机生成16位字符串
     * @return string 生成的字符串
     */
    private function randomStr()
    {
        $dictionary = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($dictionary) - 1;
        for ($i = 0, $str = ''; $i < 16; $i++) {
            $str .= $dictionary[mt_rand(0, $max)];
        }
        return $str;
    }
}
