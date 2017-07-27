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
 * Class Datacube
 * @package wesdk\service
 */
class Datacube extends BaseService
{
    /**
     * @var string
     */
    private static $URI = 'https://api.weixin.qq.com/datacube/';

    /**
     * begin_date和end_date的差值需小于最大时间跨度(比如最大时间跨度为1时,begin_date和end_date的差值只能为0才能小于1),否则会报错
     * @param string $id 接口ID
     * @param string $beginDate 获取数据的起始日期
     * @param string $endDate 获取数据的结束日期,允许设置的最大值为昨日
     * @return array|bool
     */
    private function dataCubeApi($id, $beginDate, $endDate)
    {
        $url = self::$URI . $id .'?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'begin_date' => $beginDate,
            'end_date' => $endDate,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 获取用户增减数据<br>
     * 最大时间跨度7<br>
     * begin_date和end_date的差值需小于最大时间跨度(比如最大时间跨度为1时,begin_date和end_date的差值只能为0才能小于1),否则会报错
     * @param string $beginDate 获取数据的起始日期
     * @param string $endDate 获取数据的结束日期,允许设置的最大值为昨日
     * @return array|bool
     */
    public function getUserSummary($beginDate, $endDate)
    {
        return $this->dataCubeApi('getusersummary', $beginDate, $endDate);
    }

    /**
     * 获取累计用户数据<br>
     * 最大时间跨度7<br>
     * begin_date和end_date的差值需小于最大时间跨度(比如最大时间跨度为1时,begin_date和end_date的差值只能为0才能小于1),否则会报错
     * @param string $beginDate 获取数据的起始日期
     * @param string $endDate 获取数据的结束日期,允许设置的最大值为昨日
     * @return array|bool
     */
    public function getUserCumulate($beginDate, $endDate)
    {
        return $this->dataCubeApi('getusercumulate', $beginDate, $endDate);
    }

    /**
     * 获取图文群发每日数据<br>
     * 最大时间跨度1<br>
     * begin_date和end_date的差值需小于最大时间跨度(比如最大时间跨度为1时,begin_date和end_date的差值只能为0才能小于1),否则会报错
     * @param string $beginDate 获取数据的起始日期
     * @param string $endDate 获取数据的结束日期,允许设置的最大值为昨日
     * @return array|bool
     */
    public function getArticleSummary($beginDate, $endDate)
    {
        return $this->dataCubeApi('getarticlesummary', $beginDate, $endDate);
    }

    /**
     * 获取图文群发总数据<br>
     * 最大时间跨度1<br>
     * begin_date和end_date的差值需小于最大时间跨度(比如最大时间跨度为1时,begin_date和end_date的差值只能为0才能小于1),否则会报错
     * @param string $beginDate 获取数据的起始日期
     * @param string $endDate 获取数据的结束日期,允许设置的最大值为昨日
     * @return array|bool
     */
    public function getArticleTotal($beginDate, $endDate)
    {
        return $this->dataCubeApi('getarticletotal', $beginDate, $endDate);
    }

    /**
     * 获取图文统计数据<br>
     * 最大时间跨度3<br>
     * begin_date和end_date的差值需小于最大时间跨度(比如最大时间跨度为1时,begin_date和end_date的差值只能为0才能小于1),否则会报错
     * @param string $beginDate 获取数据的起始日期
     * @param string $endDate 获取数据的结束日期,允许设置的最大值为昨日
     * @return array|bool
     */
    public function getUserRead($beginDate, $endDate)
    {
        return $this->dataCubeApi('getuserread', $beginDate, $endDate);
    }

    /**
     * 获取图文统计分时数据<br>
     * 最大时间跨度1<br>
     * begin_date和end_date的差值需小于最大时间跨度(比如最大时间跨度为1时,begin_date和end_date的差值只能为0才能小于1),否则会报错
     * @param string $beginDate 获取数据的起始日期
     * @param string $endDate 获取数据的结束日期,允许设置的最大值为昨日
     * @return array|bool
     */
    public function getUserReadHour($beginDate, $endDate)
    {
        return $this->dataCubeApi('getuserreadhour', $beginDate, $endDate);
    }

    /**
     * 获取图文分享转发数据<br>
     * 最大时间跨度7<br>
     * begin_date和end_date的差值需小于最大时间跨度(比如最大时间跨度为1时,begin_date和end_date的差值只能为0才能小于1),否则会报错
     * @param string $beginDate 获取数据的起始日期
     * @param string $endDate 获取数据的结束日期,允许设置的最大值为昨日
     * @return array|bool
     */
    public function getUserShare($beginDate, $endDate)
    {
        return $this->dataCubeApi('getusershare', $beginDate, $endDate);
    }

    /**
     * 获取图文分享转发分时数据<br>
     * 最大时间跨度1<br>
     * begin_date和end_date的差值需小于最大时间跨度(比如最大时间跨度为1时,begin_date和end_date的差值只能为0才能小于1),否则会报错
     * @param string $beginDate 获取数据的起始日期
     * @param string $endDate 获取数据的结束日期,允许设置的最大值为昨日
     * @return array|bool
     */
    public function getUserShareHour($beginDate, $endDate)
    {
        return $this->dataCubeApi('getusersharehour', $beginDate, $endDate);
    }

    /**
     * 获取消息发送概况数据<br>
     * 最大时间跨度7<br>
     * begin_date和end_date的差值需小于最大时间跨度(比如最大时间跨度为1时,begin_date和end_date的差值只能为0才能小于1),否则会报错
     * @param string $beginDate 获取数据的起始日期
     * @param string $endDate 获取数据的结束日期,允许设置的最大值为昨日
     * @return array|bool
     */
    public function getUpstreamMsg($beginDate, $endDate)
    {
        return $this->dataCubeApi('getupstreammsg', $beginDate, $endDate);
    }

    /**
     * 获取消息分送分时数据<br>
     * 最大时间跨度1<br>
     * begin_date和end_date的差值需小于最大时间跨度(比如最大时间跨度为1时,begin_date和end_date的差值只能为0才能小于1),否则会报错
     * @param string $beginDate 获取数据的起始日期
     * @param string $endDate 获取数据的结束日期,允许设置的最大值为昨日
     * @return array|bool
     */
    public function getUpstreamMsgHour($beginDate, $endDate)
    {
        return $this->dataCubeApi('getupstreammsghour', $beginDate, $endDate);
    }

    /**
     * 获取消息发送周数据<br>
     * 最大时间跨度30<br>
     * begin_date和end_date的差值需小于最大时间跨度(比如最大时间跨度为1时,begin_date和end_date的差值只能为0才能小于1),否则会报错
     * @param string $beginDate 获取数据的起始日期
     * @param string $endDate 获取数据的结束日期,允许设置的最大值为昨日
     * @return array|bool
     */
    public function getUpstreamMsgWeek($beginDate, $endDate)
    {
        return $this->dataCubeApi('getupstreammsgweek', $beginDate, $endDate);
    }

    /**
     * 获取消息发送月数据<br>
     * 最大时间跨度30<br>
     * begin_date和end_date的差值需小于最大时间跨度(比如最大时间跨度为1时,begin_date和end_date的差值只能为0才能小于1),否则会报错
     * @param string $beginDate 获取数据的起始日期
     * @param string $endDate 获取数据的结束日期,允许设置的最大值为昨日
     * @return array|bool
     */
    public function getUpstreamMsgMonth($beginDate, $endDate)
    {
        return $this->dataCubeApi('getupstreammsgmonth', $beginDate, $endDate);
    }

    /**
     * 获取消息发送分布数据<br>
     * 最大时间跨度15<br>
     * begin_date和end_date的差值需小于最大时间跨度(比如最大时间跨度为1时,begin_date和end_date的差值只能为0才能小于1),否则会报错
     * @param string $beginDate 获取数据的起始日期
     * @param string $endDate 获取数据的结束日期,允许设置的最大值为昨日
     * @return array|bool
     */
    public function getUpstreamMsgDist($beginDate, $endDate)
    {
        return $this->dataCubeApi('getupstreammsgdist', $beginDate, $endDate);
    }

    /**
     * 获取消息发送分布周数据<br>
     * 最大时间跨度30<br>
     * begin_date和end_date的差值需小于最大时间跨度(比如最大时间跨度为1时,begin_date和end_date的差值只能为0才能小于1),否则会报错
     * @param string $beginDate 获取数据的起始日期
     * @param string $endDate 获取数据的结束日期,允许设置的最大值为昨日
     * @return array|bool
     */
    public function getUpstreamMsgDistWeek($beginDate, $endDate)
    {
        return $this->dataCubeApi('getupstreammsgdistweek', $beginDate, $endDate);
    }

    /**
     * 获取消息发送分布月数据<br>
     * 最大时间跨度30<br>
     * begin_date和end_date的差值需小于最大时间跨度(比如最大时间跨度为1时,begin_date和end_date的差值只能为0才能小于1),否则会报错
     * @param string $beginDate 获取数据的起始日期
     * @param string $endDate 获取数据的结束日期,允许设置的最大值为昨日
     * @return array|bool
     */
    public function getUpstreamMsgDistMonth($beginDate, $endDate)
    {
        return $this->dataCubeApi('getupstreammsgdistmonth', $beginDate, $endDate);
    }

    /**
     * 获取接口分析数据<br>
     * 最大时间跨度30<br>
     * begin_date和end_date的差值需小于最大时间跨度(比如最大时间跨度为1时,begin_date和end_date的差值只能为0才能小于1),否则会报错
     * @param string $beginDate 获取数据的起始日期
     * @param string $endDate 获取数据的结束日期,允许设置的最大值为昨日
     * @return array|bool
     */
    public function getInterfaceSummary($beginDate, $endDate)
    {
        return $this->dataCubeApi('getinterfacesummary', $beginDate, $endDate);
    }

    /**
     * 获取接口分析分时数据<br>
     * 最大时间跨度1<br>
     * begin_date和end_date的差值需小于最大时间跨度(比如最大时间跨度为1时,begin_date和end_date的差值只能为0才能小于1),否则会报错
     * @param string $beginDate 获取数据的起始日期
     * @param string $endDate 获取数据的结束日期,允许设置的最大值为昨日
     * @return array|bool
     */
    public function getInterfaceSummaryHour($beginDate, $endDate)
    {
        return $this->dataCubeApi('getinterfacesummaryhour', $beginDate, $endDate);
    }
}
