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
 * Class Template
 * @package wesdk\service
 */
class Template extends BaseService
{
    /**
     * 设置所属行业
     * @param int $industry1 公众号模板消息所属行业编号
     * @param int $industry2 公众号模板消息所属行业编号
     * @return array|bool
     */
    public function setIndustry($industry1, $industry2)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'industry_id1' => $industry1,
            'industry_id2' => $industry2
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 获取设置的行业信息
     * @return array|bool
     */
    public function getIndustry()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/template/get_industry?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 获得模板ID(添加模板)
     * @param string $templateIdShort 模板库中模板的编号,有"TM**"和"OPENTMTM**"等形式
     * @return array|bool
     */
    public function getTplId($templateIdShort)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['template_id_short'=>$templateIdShort])));
        return $this->checkReturn($return);
    }

    /**
     * 获取模板列表
     * @return array|bool
     */
    public function getTplList()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 删除模板
     * @param string $templateId 公众帐号下模板消息ID
     * @return array|bool
     */
    public function delete($templateId)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/template/del_private_template?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['template_id'=>$templateId])));
        return $this->checkReturn($return);
    }

    /**
     * 发送模板消息
     * @param string|message\Tpl|message\Raw $data 发送的模板消息json字符串
     * @return array|bool
     */
    public function send($data)
    {
        if (is_object($data)) {
            if ($data instanceof message\Tpl) {
                $arr = [
                    'touser' => $data->touser,
                    'template_id' => $data->templateId,
                    'url' => $data->url,
                    'miniprogram' => $data->miniprogram,
                    'data' => $data->data,
                ];
                $json = $this->jsonEncode($arr);
            } elseif ($data instanceof message\Raw) {
                $json = $data->content;
            }
        } elseif (is_string($data)) {
            $json = $data;
        }
        if (isset($json)) {
            $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $this->getAccessToken();
            $return = $this->jsonDecode($this->httpPost($url, $json));
            return $this->checkReturn($return);
        }
        $this->setError(-3, '错误的消息类型');
        return false;
    }
}
