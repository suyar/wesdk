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
 * Class Media
 * @package wesdk\service
 */
class Media extends BaseService
{
    /**
     * 上传临时素材
     * ```php
     * 'image':图片,2M,支持PNG\JPEG\JPG\GIF格式
     * 'voice':语音,2M,播放长度不超过60s,支持AMR\MP3格式
     * 'video':视频,10MB,支持MP4格式
     * 'thumb':缩略图,64KB,支持JPG格式
     * ```
     * @param string $type 素材类型
     * @param \curlFile $file 要上传的文件
     * @return array|bool
     */
    private function uploadMedia($type, \CURLFile $file)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token=' . $this->getAccessToken() . '&type=' . $type;
        $return = $this->jsonDecode($this->httpPost($url, ['media'=>$file]));
        return $this->checkReturn($return);
    }

    /**
     * 上传临时素材(图片)
     * @param \CURLFile $file 要上传的文件
     * @return array|bool
     */
    public function uploadMediaImage(\CURLFile $file)
    {
        return $this->uploadMedia('image', $file);
    }

    /**
     * 上传临时素材(语音)
     * @param \CURLFile $file 要上传的文件
     * @return array|bool
     */
    public function uploadMediaVoice(\CURLFile $file)
    {
        return $this->uploadMedia('voice', $file);
    }

    /**
     * 上传临时素材(视频)
     * @param \CURLFile $file 要上传的文件
     * @return array|bool
     */
    public function uploadMediaVideo(\CURLFile $file)
    {
        return $this->uploadMedia('video', $file);
    }

    /**
     * 上传临时素材(缩略图)
     * @param \CURLFile $file 要上传的文件
     * @return array|bool
     */
    public function uploadMediaThumb(\CURLFile $file)
    {
        return $this->uploadMedia('thumb', $file);
    }

    /**
     * 下载临时素材API
     * @param string $url 要下载的素材地址
     * @param string $dir 要保存的文件夹,应存在且可写<br>
     * 如果是视频素材,$dir值等同于false的时候将不下载,并且返回视频下载地址
     * @param string $mediaId 素材ID
     * @return string|bool 成功返回文件名,失败返回false
     */
    private function downloadMediaApi($url, $dir, $mediaId)
    {
        $res = $this->httpGet($url, [
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HEADERFUNCTION => function ($ch, $header) use ($dir, &$handle, &$filename, &$lock, $mediaId) {
                $tmp = array_map('trim', explode(':', $header, 2));
                if (strtolower($tmp[0]) == 'content-disposition') {
                    if (is_dir($dir) && is_writeable($dir) && preg_match('/filename=(.*)/', $tmp[1], $matches)) {
                        $filename = $mediaId . '.' . pathinfo(basename(trim($matches[1], '"')), PATHINFO_EXTENSION);
                        if ($handle = fopen(rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . $filename, 'w')) {
                            if ($lock = flock($handle, LOCK_EX|LOCK_NB)) {
                                curl_setopt($ch, CURLOPT_FILE, $handle);
                                return strlen($header);
                            } else {
                                fclose($handle);
                            }
                        }
                    }
                    $this->setError(-4, '文件夹不可写或文件被占用');
                    return 0;
                }
                return strlen($header);
            }
        ]);

        if (is_resource($handle)) {
            $lock && flock($handle, LOCK_UN);
            fclose($handle);
            return $filename;
        } elseif(is_string($res) && ($return = $this->jsonDecode($res))) {
            if (($return = $this->checkReturn($return)) && isset($return['video_url'])) {
                return $dir ? $this->downloadMediaApi($return['video_url'], $dir, $mediaId) : $return['video_url'];
            }
        }
        return false;
    }

    /**
     * 下载临时素材
     * @param string $mediaId 素材ID
     * @param string $dir 素材要保存的目录,必须为存在的文件夹,并且可写<br>
     * 如果是视频素材,$dir值等同于false的时候将不下载,并且返回视频下载地址
     * @return string|bool 成功返回文件名,失败返回false
     */
    public function downloadMedia($mediaId, $dir)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token=' . $this->getAccessToken() . '&media_id=' . $mediaId;
        return $this->downloadMediaApi($url, $dir, $mediaId);
    }

    /**
     * 下载临时素材二进制流API
     * @param string $url 要下载的素材地址
     * @param string $contentType 文件content-type
     * @return mixed 成功返回二进制流,失败返回false
     */
    private function downloadMediaStreamApi($url, &$contentType)
    {
        $res = $this->httpGet($url, [
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HEADERFUNCTION => function ($ch, $header) use (&$contentType) {
                $tmp = array_map('trim', explode(':', $header, 2));
                if (strtolower($tmp[0]) == 'content-type') {
                    $contentType = $tmp[1];
                }
                return strlen($header);
            }
        ]);
        if ($return = $this->jsonDecode($res)) {
            if (($return = $this->checkReturn($return)) && isset($return['video_url'])) {
                return $this->downloadMediaStreamApi($return['video_url'], $contentType);
            }
            return $return;
        } else {
            return $res;
        }
    }

    /**
     * 以二进制流的形式返回素材
     * @param string $mediaId 素材ID
     * @param string $contentType 引用返回文件content-type,如:video/mp4
     * @return mixed 成功返回文件流,失败返回false
     */
    public function downloadMediaStream($mediaId, &$contentType)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token=' . $this->getAccessToken() . '&media_id=' . $mediaId;
        return $this->downloadMediaStreamApi($url, $contentType);
    }

    /**
     * 高清语音素材获取接口,获取从JSSDK的uploadVoice接口上传的临时语音素材,格式为speex
     * @param string $mediaId 媒体文件ID,即uploadVoice接口返回的serverID
     * @param string $dir 素材要保存的目录,必须为存在的文件夹,并且可写
     * @return string|bool 成功返回文件名,失败返回false
     */
    public function downloadMediaJssdkVoice($mediaId, $dir)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get/jssdk?access_token=' . $this->getAccessToken() . '&media_id=' . $mediaId;
        return $this->downloadMediaApi($url, $dir, $mediaId);
    }

    /**
     * 上传图文消息内的图片获取URL
     * 本接口所上传的图片不占用公众号的素材库中图片数量的5000个的限制,图片仅支持jpg/png格式,大小必须在1MB以下
     * @param \CURLFile $file 要上传的图片
     * @return array|bool
     */
    public function uploadArticleImg(\CURLFile $file)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, ['media'=>$file]));
        return $this->checkReturn($return);
    }

    /**
     * 新增永久图文素材
     * @param message\Article|message\Article[] $articles 图文素材内容
     * @return array|bool
     */
    public function uploadMaterialArticle($articles)
    {
        $data = ['articles'=>[]];
        if (is_array($articles)) {
            foreach ($articles as $item) {
                if (is_object($item) && $item instanceof message\Article) {
                    $data['articles'][] = [
                        'title' => $item->title,
                        'thumb_media_id' => $item->thumbMediaId,
                        'author' => $item->author,
                        'digest' => $item->digest ?: '',
                        'show_cover_pic' => $item->showCoverPic ? 1 : 0,
                        'content' => $item->content,
                        'content_source_url' => $item->contentSourceUrl,
                        'need_open_comment' => $item->needOpenComment ? 1 : 0,
                        'only_fans_can_comment' => $item->onlyFansCanComment ? 1 : 0,
                    ];
                }
            }
        } elseif (is_object($articles) && $articles instanceof message\Article) {
            $data['articles'][] = [
                'title' => $articles->title,
                'thumb_media_id' => $articles->thumbMediaId,
                'author' => $articles->author,
                'digest' => $articles->digest,
                'show_cover_pic' => $articles->showCoverPic ? 1 : 0,
                'content' => $articles->content,
                'content_source_url' => $articles->contentSourceUrl,
                'need_open_comment' => $articles->needOpenComment ? 1 : 0,
                'only_fans_can_comment' => $articles->onlyFansCanComment ? 1 : 0,
            ];
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode($data)));
        return $this->checkReturn($return);
    }

    /**
     * 修改永久图文素材
     * @param string $mediaId 要修改的图文消息的id
     * @param message\Article $article 更新成此文章
     * @param int $index 要更新的文章在图文消息中的位置,第一篇为0
     * @return array|bool
     */
    public function updateMaterialArticle($mediaId, message\Article $article, $index = 0)
    {
        $data = [
            'media_id' => $mediaId,
            'index' => $index,
            'articles' => [
                'title' => $article->title,
                'thumb_media_id' => $article->thumbMediaId,
                'author' => $article->author,
                'digest' => $article->digest ?: '',
                'show_cover_pic' => $article->showCoverPic ? 1 : 0,
                'content' => $article->content,
                'content_source_url' => $article->contentSourceUrl,
                'need_open_comment' => $article->needOpenComment ? 1 : 0,
                'only_fans_can_comment' => $article->onlyFansCanComment ? 1 : 0,
            ],
        ];
        $url = 'https://api.weixin.qq.com/cgi-bin/material/update_news?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode($data)));
        return $this->checkReturn($return);
    }

    /**
     * 上传永久素材
     * ```php
     * 'image':图片,2M,支持PNG\JPEG\JPG\GIF格式
     * 'voice':语音,2M,播放长度不超过60s,支持AMR\MP3格式
     * 'video':视频,10MB,支持MP4格式
     * 'thumb':缩略图,64KB,支持JPG格式
     * ```
     * @param string $type 素材类型
     * @param array $data 要上POST的数据
     * @return array|bool
     */
    private function uploadMaterial($type, $data)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=' . $this->getAccessToken() . '&type=' . $type;
        $return = $this->jsonDecode($this->httpPost($url, $data));
        return $this->checkReturn($return);
    }

    /**
     * 上传永久素材(图片)
     * @param \CURLFile $file 要上传的文件
     * @return array|bool
     */
    public function uploadMaterialImage(\CURLFile $file)
    {
        return $this->uploadMaterial('image', ['media'=>$file]);
    }

    /**
     * 上传永久素材(语音)
     * @param \CURLFile $file 要上传的文件
     * @return array|bool
     */
    public function uploadMaterialVoice(\CURLFile $file)
    {
        return $this->uploadMaterial('voice', ['media'=>$file]);
    }

    /**
     * 上传永久素材(视频)
     * @param \CURLFile $file 要上传的文件
     * @param string $title 视频标题
     * @param string $introduction 视频描述
     * @return array|bool
     */
    public function uploadMaterialVideo(\CURLFile $file, $title, $introduction)
    {
        return $this->uploadMaterial('video', [
            'media' => $file,
            'description' => $this->jsonEncode([
                'title' => $title,
                'introduction' => $introduction,
            ]),
        ]);
    }

    /**
     * 上传永久素材(缩略图)
     * @param \CURLFile $file 要上传的文件
     * @return array|bool
     */
    public function uploadMaterialThumb(\CURLFile $file)
    {
        return $this->uploadMaterial('thumb', ['media'=>$file]);
    }

    /**
     * 下载永久素材
     * @param string $url 要下载的素材地址
     * @param string $mediaId 素材ID
     * @param string|bool $dir 要保存的文件夹,应存在且可写;<br>
     * 如果是视频素材,$dir值等同于false的时候将不下载,并且返回['title'=>'','description'=>'','down_url'=>'','filename'=>false],filename在下载的时候才有值;<br>
     * 如果是图文素材,将返回['news_item'=>[...]],$dir值无意义<br>
     * 其他素材将下载并返回文件名
     * @return string|array|bool
     */
    private function downloadMaterialApi($url, $mediaId, $dir)
    {
        $res = $this->httpPost($url, $this->jsonEncode(['media_id'=>$mediaId]), [
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HEADERFUNCTION => function ($ch, $header) use ($dir, &$handle, &$filename, &$lock, $mediaId) {
                $tmp = array_map('trim', explode(':', $header, 2));
                if (strtolower($tmp[0]) == 'content-disposition') {
                    if (is_dir($dir) && is_writeable($dir) && preg_match('/filename=(.*)/', $tmp[1], $matches)) {
                        $filename = $mediaId . '.' . pathinfo(basename(trim($matches[1], '"')), PATHINFO_EXTENSION);
                        if ($handle = fopen(rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . $filename, 'w')) {
                            if ($lock = flock($handle, LOCK_EX|LOCK_NB)) {
                                curl_setopt($ch, CURLOPT_FILE, $handle);
                                return strlen($header);
                            } else {
                                fclose($handle);
                            }
                        }
                    }
                    $this->setError(-4, '文件夹不可写或文件被占用');
                    return 0;
                }
                return strlen($header);
            }
        ]);

        if (is_resource($handle)) {
            $lock && flock($handle, LOCK_UN);
            fclose($handle);
            return $filename;
        } elseif(is_string($res) && ($return = $this->jsonDecode($res))) {
            if ($return = $this->checkReturn($return)) {
                if (isset($return['down_url'])) {
                    $return['filename'] = $dir ? $this->downloadMaterialApi($return['down_url'], $mediaId, $dir) : false;
                }
                return $return;
            }
        }
        return false;
    }

    /**
     * 下载永久素材
     * @param string $mediaId 素材ID
     * @param string $dir 文件保存的路径,目录应存在且可写;<br>
     * 如果是视频素材,$dir值等同于false的时候将不下载,并且返回['title'=>'','description'=>'','down_url'=>'','filename'=>false],filename在下载的时候才有值;<br>
     * 如果是图文素材,将返回['news_item'=>[...]],$dir值无意义<br>
     * 其他素材将下载并返回文件名
     * @return string|array|bool
     */
    public function downloadMaterial($mediaId, $dir)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=' . $this->getAccessToken();
        return $this->downloadMaterialApi($url, $mediaId, $dir);
    }

    /**
     * 删除永久素材
     * @param string $mediaId 素材ID
     * @return array|bool
     */
    public function deleteMaterial($mediaId)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/del_material?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'media_id' => $mediaId
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 获取永久素材总数
     * @return array|bool
     */
    public function getMaterialCount()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 获取永久素材列表
     * @param string $type 可以为image,video,voice,news
     * @param int $offset 从全部素材的该偏移位置开始返回,0表示从第一个素材返回
     * @param int $count 返回素材的数量,取值在1到20之间
     * @return array|bool
     */
    public function getMaterialList($type, $offset, $count)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'type' => $type,
            'offset' => $offset,
            'count' => $count
        ])));
        return $this->checkReturn($return);
    }
}
