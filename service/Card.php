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
 * Class Card
 * @package wesdk\service
 */
class Card extends BaseService
{
    /**
     * 创建卡券
     * @param string|array $json
     * @return array|bool
     */
    public function create($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/card/create?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 设置微信买单功能接口
     * @param string $cardId 卡券ID
     * @param bool $isOpen 是否开启买单功能
     * @return array|bool
     */
    public function setPayCell($cardId, $isOpen)
    {
        $url = 'https://api.weixin.qq.com/card/paycell/set?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'card_id' => $cardId,
            'is_open' => (bool)$isOpen,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 设置自助核销功能接口
     * @param string $cardId 卡券ID
     * @param bool $isOpen 是否开启自助核销功能
     * @param bool $needVerifyCode 用户核销时是否需要输入验证码,默认否
     * @param bool $needRemarkAmount 用户核销时是否需要备注核销金额,默认否
     * @return array|bool
     */
    public function setSelfConsumeCell($cardId, $isOpen, $needVerifyCode = false, $needRemarkAmount = false)
    {
        $url = 'https://api.weixin.qq.com/card/selfconsumecell/set?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'card_id' => $cardId,
            'is_open' => (bool)$isOpen,
            'need_verify_cod' => (bool)$needVerifyCode,
            'need_remark_amount' => (bool)$needRemarkAmount,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 创建二维码接口
     * @param string|array $json
     * @return array|bool
     */
    public function createQrcode($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/card/qrcode/create?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 刷新api_ticket
     * @return bool|string
     */
    protected function refreshApiTicket()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $this->getAccessToken() . '&type=wx_card';
        $return = $this->jsonDecode($this->httpGet($url));
        if ($return = $this->checkReturn($return)) {
            $cacheKey = $this->mp->appId . 'apiticket';
            $this->setCache($cacheKey, [
                'expire' => time() + $return['expires_in'] - 200,
                'token' => $return['ticket'],
            ]);
            return $return['ticket'];
        }
        return $return;
    }

    /**
     * 获取卡券api_ticket
     * @return bool|string
     */
    public function getApiTicket()
    {
        return $this->getToken('apiticket', [$this, 'refreshApiTicket']);
    }

    /**
     * 获得卡券签名
     * @param array $data 要签名的数组,顺序无所谓,会自动过滤signature和cardSign字段
     * @return string 返回签名
     */
    public function signature($data)
    {
        if (isset($data['signature'])) {
            unset($data['signature']);
        }
        if (isset($data['cardSign'])) {
            unset($data['cardSign']);
        }
        sort($data, SORT_STRING);
        return sha1(implode($data));
    }

    /**
     * 创建货架接口
     * @param string|array $json
     * @return array|bool
     */
    public function createLandingPage($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/card/landingpage/create?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 导入code接口
     * @param string $cardId 卡券ID
     * @param string|array $codes 需导入微信卡券后台的自定义code,上限为100个
     * @return array|bool
     */
    public function deposit($cardId, $codes)
    {
        $url = 'https://api.weixin.qq.com/card/code/deposit?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'card_id' => $cardId,
            'code' => (array)$codes,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 查询导入code数目接口
     * @param string $cardId 卡券ID
     * @return array|bool
     */
    public function getDepositCount($cardId)
    {
        $url = 'https://api.weixin.qq.com/card/code/getdepositcount?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['card_id' => $cardId])));
        return $this->checkReturn($return);
    }

    /**
     * 核查code接口
     * @param string $cardId 卡券ID
     * @param string|array $codes 自定义code,上限为100个
     * @return array|bool
     */
    public function checkCode($cardId, $codes)
    {
        $url = 'https://api.weixin.qq.com/card/code/checkcode?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'card_id' => $cardId,
            'code' => (array)$codes,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 获取图文消息群发卡券的HTML
     * @param string $cardId 卡券ID
     * @return string|bool 成功返回HTML,失败返回false
     */
    public function getHtml($cardId)
    {
        $url = 'https://api.weixin.qq.com/card/mpnews/gethtml?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['card_id' => $cardId])));
        if ($return = $this->checkReturn($return)) {
            return $return['content'];
        }
        return $return;
    }

    /**
     * 设置测试白名单,不需要的参数填写空值
     * @param string|array $openid 测试的openid列表
     * @param string|array $username 测试的微信号列表
     * @return array|bool
     */
    public function setTestWhiteList($openid = [], $username = [])
    {
        $url = 'https://api.weixin.qq.com/card/testwhitelist/set?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'openid' => $openid ? (array)$openid : [],
            'username' => $username ? (array)$username : [],
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 查询Code接口
     * @param string $code 卡券code
     * @param bool $checkConsume 是否校验code核销状态
     * @param string $cardId 卡券ID,自定义code卡券必填
     * @return array|bool
     */
    public function getCode($code, $checkConsume, $cardId = '')
    {
        $url = 'https://api.weixin.qq.com/card/code/get?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'code' => $code,
            'check_consume' => (bool)$checkConsume,
            'card_id' => $cardId,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 核销Code接口
     * @param string $code 需核销的Code码
     * @param string $cardId 卡券ID,非自定义Code不必填写
     * @param string $openid 此参数线上核销卡券的时候才有用
     * @return array|bool
     */
    public function consume($code, $cardId = '', $openid = '')
    {
        $data = [
            'code' => $code,
            'card_id' => $cardId ? $cardId : '',
        ];
        $openid && ($data['openid'] = $openid);
        $url = 'https://api.weixin.qq.com/card/code/consume?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode($data)));
        return $this->checkReturn($return);
    }

    /**
     * Code解码接口
     * @param string $encryptCode 经过加密的Code码;开发者若从url上获取到加密code,请注意先进行urldecode,否则报错
     * @return array|bool
     */
    public function decryptCode($encryptCode)
    {
        $url = 'https://api.weixin.qq.com/card/code/decrypt?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'encrypt_code' => $encryptCode,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 获取用户已领取卡券接口
     * @param string $openid 需要查询的用户openid
     * @param string $cardId 卡券ID,不填写时默认查询当前appid下的卡券
     * @return array|bool
     */
    public function getUserCardList($openid, $cardId = '')
    {
        $url = 'https://api.weixin.qq.com/card/user/getcardlist?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'openid' => $openid,
            'card_id' => $cardId,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 查看卡券详情
     * @param string $cardId 卡券ID
     * @return array|bool
     */
    public function getCard($cardId)
    {
        $url = 'https://api.weixin.qq.com/card/get?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'card_id' => $cardId,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 批量查询卡券列表
     * @param int $offset 查询卡列表的起始偏移量,从0开始
     * @param int $count 需要查询的卡片的数量(数量最大50)
     * @param string|array $statusList 指定状态的卡券<br>
     * CARD_STATUS_NOT_VERIFY 待审核<br>
     * CARD_STATUS_VERIFY_FAIL 审核失败<br>
     * CARD_STATUS_VERIFY_OK 通过审核<br>
     * CARD_STATUS_DELETE 卡券被商户删除<br>
     * CARD_STATUS_DISPATCH 在公众平台投放过的卡券
     * @return array|bool
     */
    public function getCardList($offset, $count, $statusList = [])
    {
        $url = 'https://api.weixin.qq.com/card/batchget?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'offset' => $offset,
            'count' => $count,
            'status_list' => $statusList ? (array)$statusList : [],
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 更改卡券信息接口
     * @param string|array $json
     * @return array|bool
     */
    public function update($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/card/update?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 修改库存接口
     * @param string $cardId 卡券ID
     * @param int $amount 增加/减少多少库存
     * @param bool $increase 是否为增加库存,默认为是,否则为减少库存
     * @return array|bool
     */
    public function modStock($cardId, $amount, $increase = true)
    {
        $url = 'https://api.weixin.qq.com/card/modifystock?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'card_id' => $cardId,
            ($increase ? 'increase_stock_value' : 'reduce_stock_value') => $amount,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 更改Code接口
     * @param string $code 需变更的Code码
     * @param string $newCode 变更后的有效Code码
     * @param string $cardId 卡券ID,自定义Code码卡券为必填
     * @return array|bool
     */
    public function updateCode($code, $newCode, $cardId = '')
    {
        $url = 'https://api.weixin.qq.com/card/code/update?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'code' => $code,
            'card_id' => $cardId,
            'new_code' => $newCode,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 删除卡券接口
     * @param string $cardId 卡券ID
     * @return array|bool
     */
    public function delete($cardId)
    {
        $url = 'https://api.weixin.qq.com/card/delete?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'card_id' => $cardId,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 设置卡券失效接口
     * @param string $code 设置失效的Code码
     * @param string $cardId 卡券ID,自定义code必填
     * @param string $reason 失效理由
     * @return array|bool
     */
    public function unavailable($code, $cardId = '', $reason = '')
    {
        $url = 'https://api.weixin.qq.com/card/code/unavailable?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'code' => $code,
            'card_id' => $cardId,
            'reason' => $reason,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 拉取卡券概况数据接口
     * @param string $beginDate 查询数据的起始时间
     * @param string $endDate 查询数据的截至时间,查询时间区间需<=62天
     * @param int $condSource 卡券来源,0为公众平台创建的卡券数据,1是API创建的卡券数据
     * @return array|bool
     */
    public function getCardBizUinInfo($beginDate, $endDate, $condSource)
    {
        $url = 'https://api.weixin.qq.com/datacube/getcardbizuininfo?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'begin_date' => $beginDate,
            'end_date' => $endDate,
            'cond_source' => $condSource,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 获取免费券数据接口(优惠券/团购券/折扣券/礼品券)
     * @param string $beginDate 查询数据的起始时间
     * @param string $endDate 查询数据的截至时间
     * @param int $condSource 卡券来源,0为公众平台创建的卡券数据,1是API创建的卡券数据
     * @param string $cardId 卡券ID,填写后,指定拉出该卡券的相关数据
     * @return array|bool
     */
    public function getCardCardInfo($beginDate, $endDate, $condSource, $cardId = '')
    {
        $url = 'https://api.weixin.qq.com/datacube/getcardcardinfo?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'begin_date' => $beginDate,
            'end_date' => $endDate,
            'cond_source' => $condSource,
            'card_id' => $cardId,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 拉取会员卡概况数据接口
     * @param string $beginDate 查询数据的起始时间
     * @param string $endDate 查询数据的截至时间
     * @param int $condSource 卡券来源,0为公众平台创建的卡券数据,1是API创建的卡券数据
     * @return array|bool
     */
    public function getCardMemberCardInfo($beginDate, $endDate, $condSource)
    {
        $url = 'https://api.weixin.qq.com/datacube/getcardmembercardinfo?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'begin_date' => $beginDate,
            'end_date' => $endDate,
            'cond_source' => $condSource,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 拉取单张会员卡数据接口
     * @param string $beginDate 查询数据的起始时间
     * @param string $endDate 查询数据的截至时间
     * @param string $cardId 卡券ID
     * @return array|bool
     */
    public function getCardMemberCardDetail($beginDate, $endDate, $cardId)
    {
        $url = 'https://api.weixin.qq.com/datacube/getcardmembercarddetail?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'begin_date' => $beginDate,
            'end_date' => $endDate,
            'card_id' => $cardId,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 会员卡接口激活
     * @param string|array $json
     * @return array|bool
     */
    public function activateMemberCard($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/card/membercard/activate?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 设置开卡字段接口
     * @param string|array $json
     * @return array|bool
     */
    public function activateUserForm($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/card/membercard/activateuserform/set?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 拉取会员信息接口
     * @param string $cardId 卡券ID
     * @param string $code 卡券code
     * @return array|bool
     */
    public function getMemberCardUserInfo($cardId, $code)
    {
        $url = 'https://api.weixin.qq.com/card/membercard/userinfo/get?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'card_id' => $cardId,
            'code' => $code,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 获取用户提交资料
     * @param string $activateTicket 开发者在URL上截取ticket后须先进行urldecode
     * @return array|bool
     */
    public function getActivateTempInfo($activateTicket)
    {
        $url = 'https://api.weixin.qq.com/card/membercard/activatetempinfo/get?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'activate_ticket' => $activateTicket,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * (小程序)获取开卡插件参数
     * @param string $cardId 会员卡的card_id
     * @param string $outerStr 渠道值,用于统计本次领取的渠道参数
     * @return array|bool
     */
    public function getActivateUrl($cardId, $outerStr = '')
    {
        $url = 'https://api.weixin.qq.com/card/membercard/activate/geturl?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'card_id' => $cardId,
            'outer_str' => $outerStr,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 更新会员信息
     * @param string|array $json
     * @return array|bool
     */
    public function updateMemberCardUser($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/card/membercard/updateuser?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 设置支付后投放卡券规则
     * @param string|array $json
     * @return array|bool
     */
    public function addPayGiftCardRule($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/card/paygiftcard/add?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 删除支付后投放卡券规则
     * @param int $ruleId 支付即会员的规则名称
     * @return array|bool
     */
    public function delPayGiftCardRule($ruleId)
    {
        $url = 'https://api.weixin.qq.com/card/paygiftcard/delete?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['rule_id'=>$ruleId])));
        return $this->checkReturn($return);
    }

    /**
     * 查询支付后投放卡券规则详情
     * @param int $ruleId
     * @return array|bool
     */
    public function getPayGiftCardRule($ruleId)
    {
        $url = 'https://api.weixin.qq.com/card/paygiftcard/getbyid?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['rule_id' =>$ruleId])));
        return $this->checkReturn($return);
    }

    /**
     * 批量查询支付后投放卡券规则接口
     * @param int $offset 起始偏移量,0开始
     * @param int $count 查询的数量
     * @param bool $effective 是否仅查询生效的规则
     * @return array|bool
     */
    public function getPayGiftCardRules($offset, $count, $effective)
    {
        $url = 'https://api.weixin.qq.com/card/paygiftcard/batchget?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'type' => 'RULE_TYPE_PAY_MEMBER_CARD',
            'effective' => (bool)$effective,
            'offset' => $offset,
            'count' => $count,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 开通券点账户接口
     * @return array|bool
     */
    public function activatePay()
    {
        $url = 'https://api.weixin.qq.com/card/pay/activate?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 对优惠券批价
     * @param string $cardId 需要来配置库存的card_id
     * @param int $quantity 本次需要兑换的库存数目
     * @return array|bool
     */
    public function getPayPrice($cardId, $quantity)
    {
        $url = 'https://api.weixin.qq.com/card/pay/getpayprice?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'card_id' => $cardId,
            'quantity' => $quantity,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 查询券点余额接口
     * @return array|bool
     */
    public function getPayCoinsInfo()
    {
        $url = 'https://api.weixin.qq.com/card/pay/getcoinsinfo?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 确认兑换库存接口
     * @param string $cardId 需要来兑换库存的card_id
     * @param string $orderId 仅可以使用批价接口得到的订单号,保证批价有效性
     * @param int $quantity 本次需要兑换的库存数目
     * @return array|bool
     */
    public function confirmPay($cardId, $orderId, $quantity)
    {
        $url = 'https://api.weixin.qq.com/card/pay/confirm?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'card_id' => $cardId,
            'order_id' => $orderId,
            'quantity' => $quantity,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 充值券点接口
     * @param int $coinCount 需要充值的券点数目,1点=1元
     * @return array|bool
     */
    public function rechargePay($coinCount)
    {
        $url = 'https://api.weixin.qq.com/card/pay/recharge?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'coin_count' => $coinCount,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 查询订单详情接口
     * @param string $orderId
     * @return array|bool
     */
    public function getPayOrder($orderId)
    {
        $url = 'https://api.weixin.qq.com/card/pay/getorder?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'order_id' => $orderId,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 查询券点流水详情接口
     * @param string|array $json
     * @return array|bool
     */
    public function getPayOrderList($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/card/pay/getorderlist?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * Mark(占用)Code接口
     * @param string $code 卡券的code码
     * @param string $cardId 卡券的ID
     * @param string $openid 用券用户的openid
     * @param bool $isMark 是否要mark(占用)这个code,填写true或者false,表示占用或解除占用
     * @return array|bool
     */
    public function markCode($code, $cardId, $openid, $isMark)
    {
        $url = 'https://api.weixin.qq.com/card/code/mark?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'code' => $code,
            'card_id' => $cardId,
            'openid' => $openid,
            'is_mark' => (bool)$isMark,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 更新会议门票
     * @param string|array $json
     * @return array|bool
     */
    public function updateMeetingTicket($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/card/meetingticket/updateuser?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 更新电影票
     * @param string|array $json
     * @return array|bool
     */
    public function updateMovieTicket($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/card/movieticket/updateuser?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 更新飞机票信息接口
     * @param string|array $json
     * @return array|bool
     */

    public function updateBoardingPass($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/card/boardingpass/checkin?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 创建子商户接口
     * @param string|array $json
     * @return array|bool
     */
    public function createSubmerchant($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/card/submerchant/submit?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 更新子商户接口
     * @param string|array $json
     * @return array|bool
     */
    public function updateSubmerchant($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/card/submerchant/update?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 券开放类目查询接口
     * @return array|bool
     */
    public function getApplyProtocol()
    {
        $url = 'https://api.weixin.qq.com/card/getapplyprotocol?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 拉取单个子商户信息接口
     * @param int $merchantId 子商户id
     * @return array|bool
     */
    public function getSubmerchant($merchantId)
    {
        $url = 'https://api.weixin.qq.com/card/submerchant/get?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['merchant_id'=>$merchantId])));
        return $this->checkReturn($return);
    }

    /**
     * 批量拉取子商户信息接口
     * @param int $beginId 起始的子商户id
     * @param int $limit 拉取的子商户的个数,最大值为100
     * @param string $status 子商户审核状态,填入后,只会拉出当前状态的子商户<br>
     * CHECKING 审核中<br>
     * APPROVED 已通过<br>
     * REJECTED 被驳回<br>
     * EXPIRED 协议过期
     * @return array|bool
     */
    public function getSubmerchants($beginId, $limit, $status = '')
    {
        $url = 'https://api.weixin.qq.com/card/submerchant/batchget?access_token' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'begin_id' => $beginId,
            'limit' => $limit,
            'status' => $status,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 母商户资质申请接口
     * @param int $registerCapital 注册资本,数字,单位:分
     * @param string $businessLicenseMediaId 营业执照扫描件的media_id
     * @param string $taxRegistrationCertificateMediaId 税务登记证扫描件的media_id
     * @param string $lastQuarterTaxListingMediaId 上个季度纳税清单扫描件media_id
     * @return array|bool
     */
    public function uploadCardAgentQualification($registerCapital, $businessLicenseMediaId, $taxRegistrationCertificateMediaId, $lastQuarterTaxListingMediaId)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/component/upload_card_agent_qualification?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'register_capital' => $registerCapital,
            'business_license_media_id' => $businessLicenseMediaId,
            'tax_registration_certificate_media_id' => $taxRegistrationCertificateMediaId,
            'last_quarter_tax_listing_media_id' => $lastQuarterTaxListingMediaId,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 母商户资质审核查询接口
     * @return array|bool
     */
    public function checkCardAgentQualification()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/component/check_card_agent_qualification?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 子商户资质申请接口
     * @param string $appid 子商户公众号的appid
     * @param string $name 子商户商户名,用于显示在卡券券面
     * @param string $logoMeidaId 子商户logo,用于显示在子商户卡券的券面
     * @param string $businessLicenseMediaId 营业执照或个体工商户执照扫描件的media_id
     * @param string $agreementFileMediaId 子商户与第三方签署的代理授权函的media_id
     * @param int $primaryCategoryId 一级类目id
     * @param int $secondaryCategoryId 二级类目id
     * @param string $operatorIdCardMediaId 当子商户为个体工商户且无公章时,授权函须签名,并额外提交该个体工商户经营者身份证扫描件的media_id
     * @return array|bool
     */
    public function uploadCardMerchantQualification($appid, $name, $logoMeidaId, $businessLicenseMediaId, $agreementFileMediaId, $primaryCategoryId, $secondaryCategoryId, $operatorIdCardMediaId = '')
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/component/upload_card_merchant_qualification?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'appid' => $appid,
            'name' => $name,
            'logo_media_id' => $logoMeidaId,
            'business_license_media_id' => $businessLicenseMediaId,
            'operator_id_card_media_id' => $operatorIdCardMediaId,
            'agreement_file_media_id' => $agreementFileMediaId,
            'primary_category_id' => $primaryCategoryId,
            'secondary_category_id' => $secondaryCategoryId,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 子商户资质审核查询接口
     * @param string $appid 子商户appid
     * @return array|bool
     */
    public function checkCardMerchantQualification($appid)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/component/check_card_merchant_qualification?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['appid'=>$appid])));
        return $this->checkReturn($return);
    }

    /**
     * 拉取单个子商户信息接口(通过appid)
     * @param string $appid
     * @return array|bool
     */
    public function getCardMerchant($appid)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/component/get_card_merchant?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['appid'=>$appid])));
        return $this->checkReturn($return);
    }

    /**
     * 拉取子商户列表接口(appid)
     * @param string $nextGet 获取子商户列表,注意最开始时为空;每次拉取20个子商户,下次拉取时填入返回数据中该字段的值,该值无实际意义
     * @return array|bool
     */
    public function getCardMerchants($nextGet = '')
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/component/batchget_card_merchant?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['next_get'=>$nextGet])));
        return $this->checkReturn($return);
    }
}
