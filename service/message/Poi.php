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

namespace wesdk\service\message;

/**
 * Class Poi
 * @package wesdk\service\message
 */
class Poi extends MsgBase
{
    /**
     * @var int 非必填,门店标识,更新的时候必填
     */
    public $poi_id;

    /**
     * @var string 非必填,商户自己的id,用于后续审核通过收到poi_id的通知时,做对应关系,请商户自己保证唯一识别性
     */
    public $sid;

    /**
     * @var string 必填,门店名称,如:国美,麦当劳
     */
    public $business_name;

    /**
     * @var string 必填,分店名称,不应包含地区信息,不应与门店名有重复
     */
    public $branch_name;

    /**
     * @var string 必填,门店所在的省份(直辖市填城市名,如:北京市)
     */
    public $province;

    /**
     * @var string 必填,门店所在的城市
     */
    public $city;

    /**
     * @var string 必填,门店所在地区
     */
    public $district;

    /**
     * @var string 必填,门店所在的详细街道地址
     */
    public $address;

    /**
     * @var string 必填,门店的电话(纯数字,区号,分机号均由"-"隔开)
     */
    public $telephone;

    /**
     * @var string 必填,门店的类型,不同级分类用","隔开,如:"美食,小吃快餐",
     */
    public $categories;

    /**
     * @var int 必填,坐标类型,1.火星坐标 2.sogou经纬度 3.百度经纬度 4.mapbar经纬度 5.GPS坐标 6.sogou墨卡托坐标,高德经纬度无需转换可直接使用
     */
    public $offset_type;

    /**
     * @var float 必填,门店所在地理位置的经度
     */
    public $longitude;

    /**
     * @var float 必填,门店所在地理位置的纬度
     */
    public $latitude;

    /**
     * @var array 图片列表,格式['http://a.com','http://b.com']
     */
    public $photo_list;

    /**
     * @var string 非必填,推荐品,如:麦辣鸡腿堡套餐,麦乐鸡,全家桶
     */
    public $recommend;

    /**
     * @var string 非必填,特色服务,如免费wifi,免费停车,送货上门等
     */
    public $special;

    /**
     * @var string 非必填,商户简介
     */
    public $introduction;

    /**
     * @var string 非必填,营业时间,24小时制表示,用"-"连接,如8:00-20:00
     */
    public $open_time;

    /**
     * @var int 人均价格,大于0的整数
     */
    public $avg_price;
}
