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
 * Class Shake
 * @package wesdk\service
 */
class Shake extends BaseService
{
    /**
     * 申请开通摇一摇
     * @param string|array $json
     * @return array|bool
     */
    public function accountRegister($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/shakearound/account/register?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 查询审核状态
     * @return array|bool
     */
    public function accountAuditStatus()
    {
        $url = 'https://api.weixin.qq.com/shakearound/account/auditstatus?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 申请设备ID
     * @param string|array $json
     * @return array|bool
     */
    public function deviceApplyId($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/shakearound/device/applyid?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 查询设备ID申请审核状态
     * @param int $applyId 申请设备ID时所返回的批次ID
     * @return array|bool
     */
    public function deviceApplyStatus($applyId)
    {
        $url = 'https://api.weixin.qq.com/shakearound/device/applystatus?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'apply_id' => $applyId,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 编辑设备信息
     * @param string|array $json
     * @return array|bool
     */
    public function deviceUpdate($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/shakearound/device/update?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 配置设备与门店的关联关系|配置设备与其他公众账号门店的关联关系
     * @param string|array $json
     * @return array|bool
     */
    public function deviceBindLocation($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/shakearound/device/bindlocation?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 查询设备列表
     * @param string|array $json
     * @return array|bool
     */
    public function deviceSearch($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/shakearound/device/search?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 新增摇一摇出来的页面信息
     * @param string|array $json
     * @return array|bool
     */
    public function pageAdd($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/shakearound/page/add?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 编辑摇一摇出来的页面信息
     * @param string|array $json
     * @return array|bool
     */
    public function pageUpdate($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/shakearound/page/update?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 查询页面列表
     * @param string|array $json
     * @return array|bool
     */
    public function pageSearch($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/shakearound/page/search?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 删除页面
     * @param int $pageId 指定页面的id
     * @return array|bool
     */
    public function pageDelete($pageId)
    {
        $url = 'https://api.weixin.qq.com/shakearound/page/delete?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['page_id'=>$pageId])));
        return $this->checkReturn($return);
    }

    /**
     * 上传图片素材
     * @param \CURLFile $file 要上传的文件
     * @param bool $license 是否为资质文件,默认false
     * @return array|bool
     */
    public function materialAdd(\CURLFile $file, $license = false)
    {
        $url = 'https://api.weixin.qq.com/shakearound/material/add?access_token=' . $this->getAccessToken() . '&type=' . ($license ? 'license' : 'icon');
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['media'=>$file])));
        return $this->checkReturn($return);
    }

    /**
     * 配置设备与页面的关联关系
     * @param string|array $json
     * @return array|bool
     */
    public function deviceBindPage($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/shakearound/device/bindpage?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 查询设备与页面的关联关系
     * @param string|array $json
     * @return array|bool
     */
    public function deviceRelation($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/shakearound/relation/search?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 以设备为维度的数据统计接口
     * @param string $json
     * @return array|bool
     */
    public function statisticsDevice($json)
    {
        $url = 'https://api.weixin.qq.com/shakearound/statistics/device?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 批量查询设备统计数据接口
     * @param int $date 指定查询日期时间戳,单位为秒
     * @param int $pageIndex 指定查询的结果页序号,返回结果按摇周边人数降序排序,每50条记录为一页
     * @return array|bool
     */
    public function statisticsDeviceList($date, $pageIndex)
    {
        $url = 'https://api.weixin.qq.com/shakearound/statistics/devicelist?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'date' => $date,
            'page_index' => $pageIndex,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 以页面为维度的数据统计接口
     * @param int $pageId 指定页面的设备ID
     * @param int $beginDate 起始日期时间戳,最长时间跨度为30天,单位为秒
     * @param int $endDate 结束日期时间戳,最长时间跨度为30天,单位为秒
     * @return array|bool
     */
    public function statisticsPage($pageId, $beginDate, $endDate)
    {
        $url = 'https://api.weixin.qq.com/shakearound/statistics/page?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'page_id' => $pageId,
            'begin_date' => $beginDate,
            'end_date' => $endDate,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 批量查询页面统计数据接口
     * @param int $date 指定查询日期时间戳,单位为秒
     * @param int $pageIndex 指定查询的结果页序号,返回结果按摇周边人数降序排序,每50条记录为一页
     * @return array|bool
     */
    public function statisticsPageList($date, $pageIndex)
    {
        $url = 'https://api.weixin.qq.com/shakearound/statistics/pagelist?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'date' => $date,
            'page_index' => $pageIndex,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 新增分组
     * @param string $groupName 分组名称,不超过100汉字或200个英文字母
     * @return array|bool
     */
    public function groupAdd($groupName)
    {
        $url = 'https://api.weixin.qq.com/shakearound/device/group/add?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['group_name'=>$groupName])));
        return $this->checkReturn($return);
    }

    /**
     * 编辑分组信息
     * @param int $groupId 分组唯一标识,全局唯一
     * @param string $groupName 分组名称,不超过100汉字或200个英文字母
     * @return array|bool
     */
    public function groupUpdate($groupId, $groupName)
    {
        $url = 'https://api.weixin.qq.com/shakearound/device/group/update?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'group_id' => $groupId,
            'group_name' => $groupName,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 删除分组
     * @param int $groupId 分组唯一标识,全局唯一
     * @return array|bool
     */
    public function groupDelete($groupId)
    {
        $url = 'https://api.weixin.qq.com/shakearound/device/group/delete?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['group_id'=>$groupId])));
        return $this->checkReturn($return);
    }

    /**
     * 查询分组列表
     * @param int $begin 分组列表的起始索引值
     * @param int $count 待查询的分组数量,不能超过1000个
     * @return array|bool
     */
    public function groupGetList($begin, $count)
    {
        $url = 'https://api.weixin.qq.com/shakearound/device/group/getlist?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'begin' => $begin,
            'count' => $count,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 查询分组详情,包括分组名,分组id,分组里的设备列表
     * @param int $groupId 分组唯一标识,全局唯一
     * @param int $begin 分组里设备的起始索引值
     * @param int $count 待查询的分组里设备的数量，不能超过1000个
     * @return array|bool
     */
    public function groupGetDetail($groupId, $begin, $count)
    {
        $url = 'https://api.weixin.qq.com/shakearound/device/group/getdetail?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'group_id' => $groupId,
            'begin' => $begin,
            'count' => $count,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 添加设备到分组
     * @param string|array $json
     * @return array|bool
     */
    public function groupAddDevice($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/shakearound/device/group/adddevice?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 从分组中移除设备
     * @param string|array $json
     * @return array|bool
     */
    public function groupDeleteDevice($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/shakearound/device/group/deletedevice?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 获取设备及用户信息
     * @param string $ticket 摇周边业务的ticket,可在摇到的URL中得到,ticket生效时间为30分钟,每一次摇都会重新生成新的ticket
     * @param bool $needPoi 是否需要返回门店poi_id,默认否
     * @return array|bool
     */
    public function getShakeUserInfo($ticket, $needPoi = false)
    {
        $url = 'https://api.weixin.qq.com/shakearound/user/getshakeinfo?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'ticket' => $ticket,
            'need_poi' => $needPoi ? 1 : 0,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 创建红包活动,设置红包活动有效期,红包活动开关等基本信息,返回活动id
     * @param string $json
     * @param bool $useTemplate 是否使用模板,1.使用,2.不使用
     * @param string $logoUrl 使用模板页面的logo_url,不使用模板时可不填写
     * @return array|bool
     */
    public function lotteryCreate($json, $useTemplate, $logoUrl = '')
    {
        $url = 'https://api.weixin.qq.com/shakearound/lottery/addlotteryinfo?access_token=' . $this->getAccessToken() . '&use_template=' . ($useTemplate ? '1' : '2') . '&logo_url=' . urlencode($logoUrl);
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 录入红包信息
     * @param string|array $json
     * @return array|bool
     */
    public function lotterySetPrizeBucket($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/shakearound/lottery/setprizebucket?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 设置红包活动抽奖开关
     * @param string $lotteryId 红包抽奖id,来自addlotteryinfo返回的lottery_id
     * @param bool $on true开启,false关闭
     * @return array|bool
     */
    public function lotterySetSwitch($lotteryId, $on)
    {
        $url = 'https://api.weixin.qq.com/shakearound/lottery/setlotteryswitch?access_token='.$this->getAccessToken() . '&lottery_id=' . $lotteryId . '&onoff=' . ($on ? '1' : '0');
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 红包查询接口
     * @param string $lotteryId
     * @return array|bool
     */
    public function lotteryQuery($lotteryId)
    {
        $url = 'https://api.weixin.qq.com/shakearound/lottery/querylottery?access_token='.$this->getAccessToken() . '&lottery_id=' . $lotteryId;
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }
}
