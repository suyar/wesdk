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
 * Class CSMessage
 * @package wesdk\service
 */
class CSMessage extends BaseService
{
    /**
     * 发送客服消息
     * @param string|message\Text|message\Image|message\Voice|message\Video|message\Music|message\News|message\News[]|message\Raw|message\Mpnews|message\Card $message 发送的消息
     * @param string $openid 用户的openid
     * @param string $account 客服账号
     * @return array|bool
     */
    public function send($message, $openid, $account = null)
    {
        if (is_array($message) && $message) {
            $obj = current($message);
            if (is_object($obj) && $obj instanceof message\News) {
                $class = get_class($obj);
            }
        } elseif (is_object($message) && $message instanceof message\MsgBase) {
            $class = get_class($message);
        } elseif (is_string($message) && $message) {
            $message = new message\Text(['content'=>$message]);
            $class = message\Text::class;
        }
        if (isset($class)) {
            $data = ['touser'=>$openid];
            $account && ($data['customservice']['kf_account'] = $account);
            $class = strtolower(substr($class, strrpos($class, '\\') + 1));
            switch ($class) {
                case 'text':
                    $data['msgtype'] = 'text';
                    $data['text']['content'] = $message->content;
                    $data = $this->jsonEncode($data);
                    break;
                case 'image':
                    $data['msgtype'] = 'image';
                    $data['image']['media_id'] = $message->mediaId;
                    $data = $this->jsonEncode($data);
                    break;
                case 'voice':
                    $data['msgtype'] = 'voice';
                    $data['voice']['media_id'] = $message->mediaId;
                    $data = $this->jsonEncode($data);
                    break;
                case 'video':
                    $data['msgtype'] = 'video';
                    $data['video'] = [
                        'media_id' => $message->mediaId,
                        'thumb_media_id' => $message->thumbMediaId,
                        'title' => $message->title,
                        'description' => $message->description,
                    ];
                    $data = $this->jsonEncode($data);
                    break;
                case 'music':
                    $data['msgtype'] = 'music';
                    $data['music'] = [
                        'media_id' => $message->mediaId,
                        'title' => $message->title,
                        'description' => $message->description,
                        'musicurl' => $message->musicURL,
                        'hqmusicurl' => $message->HQMusicUrl,
                        'thumb_media_id' => $message->thumbMediaId,
                    ];
                    $data = $this->jsonEncode($data);
                    break;
                case 'news':
                    $data['msgtype'] = 'news';
                    $data['news'] = ['articles' => []];
                    if (is_array($message)) {
                        foreach ($message as $item) {
                            if (is_object($item) && $item instanceof message\News) {
                                $data['news']['articles'][] = [
                                    'title' => $item->title,
                                    'description' => $item->description,
                                    'url' => $item->url,
                                    'picurl' => $item->picUrl,
                                ];
                            }
                        }
                    } elseif ($message instanceof message\News) {
                        $data['news']['articles'][] = [
                            'title' => $message->title,
                            'description' => $message->description,
                            'url' => $message->url,
                            'picurl' => $message->picUrl,
                        ];
                    }
                    $data = $this->jsonEncode($data);
                    break;
                case 'mpnews':
                    $data['msgtype'] = 'mpnews';
                    $data['mpnews']['media_id'] = $message->mediaId;
                    $data = $this->jsonEncode($data);
                    break;
                case 'card':
                    $data['msgtype'] = 'wxcard';
                    $data['wxcard']['card_id'] = $message->cardId;
                    $data = $this->jsonEncode($data);
                    break;
                case 'raw':
                    $data = $message->content;
                    break;
                default:
                    return false;
            }
            return $this->post($data);
        } else {
            $this->setError(-3, '错误的消息类型');
            return false;
        }
    }

    /**
     * 发送数据
     * @param string $json
     * @return array|bool
     */
    private function post($json)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }
}
