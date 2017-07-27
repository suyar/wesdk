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
 * Class Push
 * @package wesdk\service
 */
class Push extends BaseService
{
    /**
     * 转换群发视频,只有临时素材的视频需要转换
     * ```php
     * 狗日的微信开发,写文档的时候不说清楚,只有临时视频素材才需要转换,永久视频素材不需要转换
     * ```
     * @param message\Video $video
     * @return array|bool
     */
    public function transformVideo(message\Video $video)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/uploadvideo?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'media_id' => $video->mediaId,
            'title' => $video->title,
            'description' => $video->description,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 群发消息
     * @param string|message\Mpnews|message\Text|message\Voice|message\Image|message\Video|message\Card|message\Raw $message
     * @param null|int|array $to
     * 当此参数为null的时候,发送给所有人<br>
     * 当此参数为int的时候,发送给指定分组<br>
     * 当此参数为array的时候,发送给指定用户(至少两个openid)
     * @param null|bool $sendIgnoreReprint 文章被判定为转载时,是否继续群发;当类型为Mpnews的时候才有效
     * @return array|bool
     */
    public function push($message, $to = null, $sendIgnoreReprint = null)
    {
        if (is_object($message) && $message instanceof message\MsgBase) {
            $type = get_class($message);
        } elseif (is_string($message) && $message) {
            $message = new message\Text(['content'=>$message]);
            $type = message\Text::class;
        }
        if (isset($type)) {
            $type = strtolower(substr($type, strrpos($type, '\\') + 1));
            $data = $this->buildArray($type, $message);
            if (is_array($data)) {
                $type == 'mpnews' && ($data['send_ignore_reprint'] = $sendIgnoreReprint ? 1 : 0);
                if (is_array($to)) {
                    $data['touser'] = $to;
                    return $this->sendUserApi($this->jsonEncode($data));
                } else {
                    $data['filter'] = [
                        'is_to_all' => $to === null ? false : true,
                        'tag_id' => intval($to),
                    ];
                    return $this->sendAllApi($this->jsonEncode($data));
                }
            } elseif (is_string($data)) {
                return is_array($to) ? $this->sendUserApi($data) : $this->sendAllApi($data);
            }
        }
        $this->setError(-3, '错误的消息类型');
        return false;
    }

    /**
     * 组建消息数组
     * @param string $type
     * @param string|message\Mpnews|message\Text|message\Voice|message\Image|message\Video|message\Card|message\Raw $message
     * @return bool|array
     */
    private function buildArray($type, $message)
    {
        switch ($type) {
            case 'mpnews':
                $data['msgtype'] = 'mpnews';
                $data['mpnews']['media_id'] = $message->mediaId;
                break;
            case 'text':
                $data['msgtype'] = 'text';
                $data['text']['content'] = $message->content;
                break;
            case 'voice':
                $data['msgtype'] = 'voice';
                $data['voice']['media_id'] = $message->mediaId;
                break;
            case 'image':
                $data['msgtype'] = 'image';
                $data['image']['media_id'] = $message->mediaId;
                break;
            case 'video':
                $data['msgtype'] = 'mpvideo';
                $data['mpvideo']['media_id'] = $message->mediaId;
                break;
            case 'card':
                $data['msgtype'] = 'wxcard';
                $data['wxcard']['card_id'] = $message->cardId;
                break;
            case 'raw':
                $data = $message->content;
                break;
            default:
                return false;
        }
        return $data;
    }

    /**
     * 根据标签进行群发
     * @param string $json
     * @return array|bool
     */
    private function sendAllApi($json)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 根据openid进行群发
     * @param string $json
     * @return array|bool
     */
    private function sendUserApi($json)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 删除群发
     * ```php
     * 1.只有已经发送成功的消息才能删除
     * 2.删除消息是将消息的图文详情页失效,已经收到的用户,还是能在其本地看到消息卡片
     * 3.删除群发消息只能删除图文消息和视频消息,其他类型的消息一经发送,无法删除
     * 4.如果多次群发发送的是一个图文消息,那么删除其中一次群发,就会删除掉这个图文消息也会导致所有群发都失效
     * ```
     * @param int $msgId 发送出去的消息ID
     * @param int $index 要删除的文章在图文消息中的位置,第一篇编号为1,该字段不填或填0会删除全部文章
     * @return array|bool
     */
    public function delete($msgId, $index = 0)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/delete?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'msg_id' => $msgId,
            'article_idx' => intval($index)
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 预览接口
     * @param string|message\Mpnews|message\Text|message\Voice|message\Image|message\Video|message\Card|message\Raw $message
     * @param string $to openid或者微信号
     * @param bool $isWx 当此字段为true的时候,$to可以设置为微信号
     * @return array|bool
     */
    public function preview($message, $to, $isWx = false)
    {
        if (is_object($message) && $message instanceof message\MsgBase) {
            $type = get_class($message);
        } elseif (is_string($message) && $message) {
            $message = new message\Text(['content'=>$message]);
            $type = message\Text::class;
        }
        if (isset($type)) {
            $type = strtolower(substr($type, strrpos($type, '\\') + 1));
            $data = $this->buildArray($type, $message);
            if (is_array($data)) {
                $isWx ? ($data['towxname'] = $to) : ($data['touser'] = $to);
                return $this->previewApi($this->jsonEncode($data));
            } elseif (is_string($data)) {
                return $this->previewApi($data);
            }
        }
        $this->setError(-3, '错误的消息类型');
        return false;
    }

    /**
     * 预览接口
     * @param string $json
     * @return array|bool
     */
    private function previewApi($json)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 查询群发消息发送状态
     * @param int $msgId 群发消息后返回的消息id
     * @return array|bool
     */
    public function sendStatus($msgId)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/get?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['msg_id'=>$msgId])));
        return $this->checkReturn($return);
    }
}
