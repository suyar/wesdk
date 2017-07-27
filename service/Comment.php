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
 * Class Comment
 * @package wesdk\service
 */
class Comment extends BaseService
{
    /**
     * @var string
     */
    private static $URI = 'https://api.weixin.qq.com/cgi-bin/comment/';

    /**
     * 打开已群发文章评论
     * @param int $msgDataId 群发返回的msg_data_id
     * @param int $index 指定第几篇图文,从0开始
     * @return array|bool
     */
    public function open($msgDataId, $index = 0)
    {
        $url = self::$URI . 'open?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'msg_data_id' => $msgDataId,
            'index' => $index,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 关闭已群发文章评论
     * @param int $msgDataId 群发返回的msg_data_id
     * @param int $index 指定第几篇图文,从0开始
     * @return array|bool
     */
    public function close($msgDataId, $index = 0)
    {
        $url = self::$URI . 'close?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'msg_data_id' => $msgDataId,
            'index' => $index,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 查看指定文章的评论数据
     * @param int $msgDataId 群发返回的msg_data_id
     * @param int $begin 起始位置
     * @param int $count 获取数目(>=50会被拒绝)
     * @param int $type 0所有评论,1普通评论,2精选评论
     * @param int $index 指定第几篇图文,从0开始
     * @return array|bool
     */
    public function lists($msgDataId, $begin, $count, $type, $index = 0)
    {
        $url = self::$URI . 'list?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'msg_data_id' => $msgDataId,
            'index' => $index,
            'begin' => $begin,
            'count' => $count,
            'type' => $type,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 将评论标记精选
     * @param int $msgDataId 群发返回的msg_data_id
     * @param int $userCommentId 用户评论id
     * @param int $index 指定第几篇图文,从0开始
     * @return array|bool
     */
    public function mark($msgDataId, $userCommentId, $index = 0)
    {
        $url = self::$URI . 'markelect?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'msg_data_id' => $msgDataId,
            'index' => $index,
            'user_comment_id' => $userCommentId,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 将评论取消精选
     * @param int $msgDataId 群发返回的msg_data_id
     * @param int $userCommentId 用户评论id
     * @param int $index 指定第几篇图文,从0开始
     * @return array|bool
     */
    public function unmark($msgDataId, $userCommentId, $index = 0)
    {
        $url = self::$URI . 'unmarkelect?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'msg_data_id' => $msgDataId,
            'index' => $index,
            'user_comment_id' => $userCommentId,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 删除评论
     * @param int $msgDataId 群发返回的msg_data_id
     * @param int $userCommentId 用户评论id
     * @param int $index 指定第几篇图文,从0开始
     * @return array|bool
     */
    public function delete($msgDataId, $userCommentId, $index = 0)
    {
        $url = self::$URI . 'delete?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'msg_data_id' => $msgDataId,
            'index' => $index,
            'user_comment_id' => $userCommentId,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 回复评论
     * @param int $msgDataId 群发返回的msg_data_id
     * @param int $userCommentId 用户评论id
     * @param string $content 回复内容
     * @param int $index 指定第几篇图文,从0开始
     * @return array|bool
     */
    public function addReply($msgDataId, $userCommentId, $content, $index = 0)
    {
        $url = self::$URI . 'reply/add?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'msg_data_id' => $msgDataId,
            'index' => $index,
            'user_comment_id' => $userCommentId,
            'content' => $content,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 删除回复
     * @param int $msgDataId 群发返回的msg_data_id
     * @param int $userCommentId 用户评论id
     * @param int $index 指定第几篇图文,从0开始
     * @return array|bool
     */
    public function deleteReply($msgDataId, $userCommentId, $index = 0)
    {
        $url = self::$URI . 'reply/delete?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'msg_data_id' => $msgDataId,
            'index' => $index,
            'user_comment_id' => $userCommentId,
        ])));
        return $this->checkReturn($return);
    }
}
