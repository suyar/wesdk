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
 * Class Messgae
 * @package wesdk\service
 *
 * @property string $ToUserName 接收方账号
 * @property string $FromUserName 发送方帐号
 * @property string $CreateTime 消息创建时间
 * @property string $MsgType 消息类型
 * @property string $Content 文本消息内容
 * @property string $MsgId 消息ID
 * @property string $PicUrl 图片链接
 * @property string $MediaId 消息媒体id,可以调用多媒体文件下载接口拉取数据
 * @property string $Format 语音格式,如amr,speex等
 * @property string $Recognition 语音识别结果,UTF8编码
 * @property string $ThumbMediaId 视频消息缩略图的媒体id,可以调用多媒体文件下载接口拉取数据
 * @property string $Location_X 地理位置维度
 * @property string $Location_Y 地理位置经度
 * @property string $Scale 地图缩放大小
 * @property string $Label 地理位置信息
 * @property string $Title (链接)消息标题
 * @property string $Description (链接)消息描述
 * @property string $Url (链接)URL
 *
 * @property string $Event 事件类型
 * @property string $EventKey 事件KEY值,qrscene_为前缀,后面为二维码的参数值
 * @property string $Ticket 二维码的ticket,可用来换取二维码图片
 * @property string $Latitude (上报)地理位置纬度
 * @property string $Longitude (上报)地理位置经度
 * @property string $Precision (上报)地理位置精度
 *
 * @property string $MenuId 指菜单ID,如果是个性化菜单,则可以通过这个字段,知道是哪个规则的菜单被点击了
 * @property array $ScanCodeInfo 扫描信息
 * @property array $SendPicsInfo 发送的图片信息
 * @property array $SendLocationInfo 发送的位置信息
 *
 * @property string $MsgID 群发的消息ID
 * @property string $Status 群发的结果
 * @property string $TotalCount tag_id下粉丝数,或者openid_list中的粉丝数
 * @property string $FilterCount 过滤(过滤是指特定地区,性别的过滤,用户设置拒收的过滤,用户接收已超4条的过滤)后,准备发送的粉丝数,原则上FilterCount=SentCount+ErrorCount
 * @property string $SentCount 发送成功的粉丝数
 * @property string $ErrorCount 发送失败的粉丝数
 * @property array $CopyrightCheckResult 原创校验结果
 *
 * @property string $ExpiredTime 有效期(整型),指的是时间戳,将于该时间戳认证过期
 * @property string $FailTime 认证失败发生时间(整型),时间戳
 * @property string $FailReason 认证失败的原因
 *
 * @property string $CardId 卡券ID
 * @property string $RefuseReason 审核不通过原因
 * @property string $IsGiveByFriend 是否为转赠领取,1代表是,0代表否
 * @property string $UserCardCode code序列号
 * @property string $FriendUserName 当IsGiveByFriend为1时填入的字段,表示发起转赠用户的openid
 * @property string $OuterId
 * @property string $OldUserCardCode 为保证安全,微信会在转赠发生后变更该卡券的code号,该字段表示转赠前的code
 * @property string $OuterStr 领取场景值,用于领取渠道数据统计,可在生成二维码接口及添加Addcard接口中自定义该字段的字符串值
 * @property string $IsRestoreMemberCard 用户删除会员卡后可重新找回,当用户本次操作为找回时,该值为1,否则为0
 * @property string $IsRecommendByFriend
 * @property string $IsReturnBack 是否转赠退回,0代表不是,1代表是
 * @property string $IsChatRoom 是否是群转赠
 * @property string $ConsumeSource 核销来源
 * @property string $LocationName 门店名称,当前卡券核销的门店名称(只有通过自助核销和买单核销时才会出现该字段)
 * @property string $StaffOpenId 核销该卡券核销员的openid(只有通过卡券商户助手核销时才会出现)
 * @property string $VerifyCode 自助核销时,用户输入的验证码
 * @property string $RemarkAmount 自助核销时,用户输入的备注金额
 * @property string $TransId 微信支付交易订单号(只有使用买单功能核销的卡券才会出现)
 * @property string $LocationId 门店ID,当前卡券核销的门店ID(只有通过卡券商户助手和买单核销时才会出现)
 * @property string $Fee 实付金额,单位为分
 * @property string $OriginalFee 应付金额,单位为分
 * @property string $ModifyBonus 变动的积分值
 * @property string $ModifyBalance 变动的余额值
 * @property string $Detail 报警详细信息
 * @property string $OrderId 本次推送对应的订单号
 * @property string $CreateOrderTime 购买券点时,支付二维码的生成时间
 * @property string $PayFinishTime 购买券点时,实际支付成功的时间
 * @property string $Desc 支付方式,一般为微信支付充值
 * @property string $FreeCoinCount 剩余免费券点数量
 * @property string $PayCoinCount 剩余付费券点数量
 * @property string $RefundFreeCoinCount 本次变动的免费券点数量
 * @property string $RefundPayCoinCount 本次变动的付费券点数量
 * @property string $OrderType 所要拉取的订单类型
 * @property string $Memo 系统备注,说明此次变动的缘由
 * @property string $ReceiptInfo 所开发票的详情
 * @property string $MerchantId 子商户ID
 * @property string $IsPass 是否通过,为1时审核通过
 * @property string $Reason 驳回的原因
 * @property string $AppId 公众号第三方平台账号(即母商户)的AppID
 * @property string $InfoType 消息类型,card_merchant_auth_check_result(第三方开发者代制有公众号模式,子商户资料审核事件)
 * @property string $SubMerchantAppId 子商户账号的AppID
 *
 * @property string $UniqId 商户自己内部ID,即字段中的sid
 * @property string $PoiId 微信的门店ID,微信内门店唯一标示ID
 * @property string $Result 审核结果,成功succ或失败fail
 * @property string $msg 成功的通知信息,或审核失败的驳回理由
 * @property string $audit_id 创建小程序门店审核单id
 * @property string $status 小程序门店审核状态(1.审核通过,3.审核失败,4.管理员拒绝)
 * @property string $reason 小程序门店,如果status为3或者4,会返回审核失败的原因
 * @property string $map_poi_id 腾讯地图中创建门店,从腾讯地图换取的位置点id
 * @property string $name 腾讯地图中创建门店,门店名字
 * @property string $address 腾讯地图中创建门店,详细地址
 * @property string $latitude 腾讯地图中创建门店,经度
 * @property string $longitude 腾讯地图中创建门店,纬度
 * @property string $sh_remark 腾讯地图中创建门店,备注
 * @property string $is_upgrade 0表示创建门店,1表示是补充门店
 * @property string $poiid 小程序门店id
 *
 * @property string $OrderStatus 微信小店付款通知,订单状态
 * @property string $ProductId 微信小店付款通知,商品ID
 * @property string $SkuInfo 微信小店付款通知,商品SKU
 *
 * @property array $ChosenBeacon 摇一摇,选择的设备
 * @property array $AroundBeacons 摇一摇,周边的设备
 *
 * @property string $LotteryId 红包绑定用户,红包活动ID
 * @property string $Money 红包绑定用户,红包金额
 * @property string $BindTime 红包绑定用户,红包绑定时间
 *
 * @property string $ConnectTime 连网时间(整型)
 * @property string $ExpireTime 系统保留字段,固定值
 * @property string $VendorId 系统保留字段,固定值
 * @property string $ShopId 门店ID,即shop_id
 * @property string $DeviceNo 连网的设备无线mac地址,对应bssid
 *
 * @property string $KeyStandard 商品编码标准
 * @property string $KeyStr 商品编码内容
 * @property string $Country 用户在微信内设置的国家
 * @property string $Province 用户在微信内设置的省份
 * @property string $City 用户在微信内设置的城市
 * @property string $Sex 用户的性别,1为男性,2为女性,0代表未知
 * @property string $Scene 打开商品主页的场景,1为扫码,2为其他打开场景(如会话、收藏或朋友圈)
 * @property string $ExtInfo 调用"获取商品二维码接口"时传入的extinfo,为标识参数
 * @property string $RegionCode 用户的实时地理位置信息(目前只精确到省一级),可在国家统计局网站查到对应明细
 * @property string $ReasonMsg 审核未通过的原因
 * @property string $NeedAntiFake 是否使用微信提供的弹窗页面展示防伪结果,true为使用,false为未使用
 *
 * @property string $appid 公众账号ID
 * @property string $mch_id 商户号
 * @property string $device_info 设备号
 * @property string $nonce_str 随机字符串
 * @property string $sign 签名
 * @property string $sign_type 签名类型,目前支持HMAC-SHA256和MD5,默认为MD5
 * @property string $result_code 业务结果SUCCESS/FAIL
 * @property string $err_code 错误代码
 * @property string $err_code_des 错误代码描述
 * @property string $openid 用户标识
 * @property string $is_subscribe 用户是否关注公众账号,Y-关注,N-未关注,仅在公众账号类型支付有效
 * @property string $trade_type 交易类型,JSAPI,NATIVE,APP
 * @property string $bank_type 付款银行
 * @property string $total_fee 订单总金额,单位为分
 * @property string $settlement_total_fee 应结订单金额=订单金额-非充值代金券金额,应结订单金额<=订单金额
 * @property string $fee_type 货币种类
 * @property string $cash_fee 现金支付金额
 * @property string $cash_fee_type 现金支付货币类型
 * @property string $coupon_fee 代金券金额<=订单金额,订单金额-代金券金额=现金支付金额
 * @property string $coupon_count 代金券使用数量
 * @property string $coupon_type_0 代金券类型,还有coupon_type_0等
 * @property string $coupon_id_0 代金券ID,还有coupon_id_1等
 * @property string $coupon_fee_0 单个代金券支付金额,还有coupon_fee_1等
 * @property string $transaction_id 微信支付订单号
 * @property string $out_trade_no 商户订单号
 * @property string $attach 商家数据包
 * @property string $time_end 支付完成时间
 * @property string $return_code 返回状态码,SUCCESS/FAIL;此字段是通信标识,非交易标识,交易是否成功需要查看result_code来判断
 * @property string $return_msg 返回信息,如非空,为错误原因:签名失败或者参数格式校验错误
 * @property string $req_info 退款结果加密信息,用户自行解密
 */
class Messgae
{
    /**
     * @var array
     */
    private $attr;

    /**
     * Messgae constructor.
     * @param $attr
     */
    public function __construct($attr)
    {
        $this->attr = $attr;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->__isset($name) ? $this->attr[$name] : null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->attr[$name]);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->attr[$name] = $value;
    }

    /**
     * @param string $name
     */
    public function __unset($name)
    {
        if ($this->__isset($name)) {
            unset($this->attr[$name]);
        }
    }
}
