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
 * Class Shop
 * @package wesdk\service
 */
class Shop extends BaseService
{
    /**
     * 增加商品
     * @param string|array $json
     * @return array|bool
     */
    public function create($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/merchant/create?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 删除商品
     * @param string $productId 商品ID
     * @return array|bool
     */
    public function delete($productId)
    {
        $url = 'https://api.weixin.qq.com/merchant/del?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['product_id'=>$productId])));
        return $this->checkReturn($return);
    }

    /**
     * 修改商品
     * @param string|array $json
     * @return array|bool
     */
    public function update($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/merchant/update?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 查询商品
     * @param string $productId 商品ID
     * @return array|bool
     */
    public function getInfo($productId)
    {
        $url = 'https://api.weixin.qq.com/merchant/get?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['product_id'=>$productId])));
        return $this->checkReturn($return);
    }

    /**
     * 获取指定状态的所有商品
     * @param int $status 商品状态(0:全部,1:上架,2:下架)
     * @return array|bool
     */
    public function getByStatus($status)
    {
        $url = 'https://api.weixin.qq.com/merchant/getbystatus?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['status'=>$status])));
        return $this->checkReturn($return);
    }

    /**
     * 商品上下架
     * @param string $productId 商品ID
     * @param int $status 商品上下架标识(0:下架,1:上架)
     * @return array|bool
     */
    public function modStatus($productId, $status)
    {
        $url = 'https://api.weixin.qq.com/merchant/modproductstatus?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'product_id' => $productId,
            'status' => $status,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 获取指定分类的所有子分类
     * @param int $cateId 大分类ID(根节点分类id为1)
     * @return array|bool
     */
    public function getSubCate($cateId)
    {
        $url = 'https://api.weixin.qq.com/merchant/category/getsub?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['cate_id'=>$cateId])));
        return $this->checkReturn($return);
    }

    /**
     * 获取指定子分类的所有SKU
     * @param int $cateId 商品子分类ID
     * @return array|bool
     */
    public function getCateSku($cateId)
    {
        $url = 'https://api.weixin.qq.com/merchant/category/getsku?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['cate_id'=>$cateId])));
        return $this->checkReturn($return);
    }

    /**
     * 获取指定分类的所有属性
     * @param int $cateId 分类ID
     * @return array|bool
     */
    public function getCatePro($cateId)
    {
        $url = 'https://api.weixin.qq.com/merchant/category/getproperty?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['cate_id'=>$cateId])));
        return $this->checkReturn($return);
    }

    /**
     * 增加库存
     * @param string $productId 商品ID
     * @param string $skuInfo sku信息,格式"id1:vid1;id2:vid2",如商品为统一规格,则此处赋值为空字符串即可
     * @param int $quantity 增加的库存数量
     * @return array|bool
     */
    public function addStock($productId, $skuInfo, $quantity)
    {
        $url = 'https://api.weixin.qq.com/merchant/stock/add?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'product_id' => $productId,
            'sku_info' => $skuInfo,
            'quantity' => $quantity,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 减少库存
     * @param string $productId 商品ID
     * @param string $skuInfo sku信息,格式"id1:vid1;id2:vid2",如商品为统一规格,则此处赋值为空字符串即可
     * @param int $quantity 增加的库存数量
     * @return array|bool
     */
    public function reduceStock($productId, $skuInfo, $quantity)
    {
        $url = 'https://api.weixin.qq.com/merchant/stock/reduce?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'product_id' => $productId,
            'sku_info' => $skuInfo,
            'quantity' => $quantity,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 增加邮费模板
     * @param string $json
     * @return array|bool
     */
    public function addExpress($json)
    {
        $url = 'https://api.weixin.qq.com/merchant/express/add?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 删除邮费模板
     * @param int $templateId 邮费模板ID
     * @return array|bool
     */
    public function delExpress($templateId)
    {
        $url = 'https://api.weixin.qq.com/merchant/express/del?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['template_id'=>$templateId])));
        return $this->checkReturn($return);
    }

    /**
     * 修改邮费模板
     * @param string|array $json
     * @return array|bool
     */
    public function updateExpress($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/merchant/express/update?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 获取指定ID的邮费模板
     * @param int $templateId 邮费模板ID
     * @return array|bool
     */
    public function getExpressById($templateId)
    {
        $url = 'https://api.weixin.qq.com/merchant/express/getbyid?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['template_id'=>$templateId])));
        return $this->checkReturn($return);
    }

    /**
     * 获取所有邮费模板
     * @return array|bool
     */
    public function getAllExpress()
    {
        $url = 'https://api.weixin.qq.com/merchant/express/getall?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 增加分组
     * @param string $groupName 分组名称
     * @param string|array $products 商品ID或者商品ID数组
     * @return array|bool
     */
    public function addGroup($groupName, $products)
    {
        $url = 'https://api.weixin.qq.com/merchant/group/add?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'group_detail' => [
                'group_name' => $groupName,
                'product_list' => (array)$products,
            ],
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 删除分组
     * @param int $groupId 分组ID
     * @return array|bool
     */
    public function delGroup($groupId)
    {
        $url = 'https://api.weixin.qq.com/merchant/group/del?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['group_id'=>$groupId])));
        return $this->checkReturn($return);
    }

    /**
     * 修改分组属性
     * @param int $groupId 分组ID
     * @param string $groupName 分组名称
     * @return array|bool
     */
    public function modGroupProp($groupId, $groupName)
    {
        $url = 'https://api.weixin.qq.com/merchant/group/propertymod?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'group_id' => $groupId,
            'group_name' => $groupName,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 修改分组商品
     * @param int $groupId 分组ID
     * @param array $products 分组的商品集合,[['product_id'=>'','mod_action'=>1],['product_id'=>'','mod_action'=>0]
     * @return array|bool
     * @internal param string $groupName 分组名称
     */
    public function modGroupProduct($groupId, $products)
    {
        $url = 'https://api.weixin.qq.com/merchant/group/productmod?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode([
            'group_id' => $groupId,
            'product' => $products,
        ])));
        return $this->checkReturn($return);
    }

    /**
     * 获取所有分组
     * @return array|bool
     */
    public function getAllGroup()
    {
        $url = 'https://api.weixin.qq.com/merchant/group/getall?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 根据分组ID获取分组信息
     * @param int $groupId 分组ID
     * @return array|bool
     */
    public function getGroupById($groupId)
    {
        $url = 'https://api.weixin.qq.com/merchant/group/getbyid?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['group_id'=>$groupId])));
        return $this->checkReturn($return);
    }

    /**
     * 增加货架
     * @param string|array $json
     * @return array|bool
     */
    public function addShelf($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/merchant/shelf/add?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 删除货架
     * @param int $shelfId 货架ID
     * @return array|bool
     */
    public function delShelf($shelfId)
    {
        $url = 'https://api.weixin.qq.com/merchant/shelf/del?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['shelf_id'=>$shelfId])));
        return $this->checkReturn($return);
    }

    /**
     * 修改货架
     * @param string|array $json
     * @return array|bool
     */
    public function modShelf($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/merchant/shelf/mod?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 获取所有货架
     * @return array|bool
     */
    public function getAllShelf()
    {
        $url = 'https://api.weixin.qq.com/merchant/shelf/getall?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpGet($url));
        return $this->checkReturn($return);
    }

    /**
     * 根据货架ID获取货架信息
     * @param int $shelfId 货架ID
     * @return array|bool
     */
    public function getShelfById($shelfId)
    {
        $url = 'https://api.weixin.qq.com/merchant/shelf/getbyid?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['shelf_id'=>$shelfId])));
        return $this->checkReturn($return);
    }

    /**
     * 根据订单ID获取订单详情
     * @param string $orderId 订单ID
     * @return array|bool
     */
    public function getOrderById($orderId)
    {
        $url = 'https://api.weixin.qq.com/merchant/order/getbyid?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['order_id'=>$orderId])));
        return $this->checkReturn($return);
    }

    /**
     * 根据订单状态/创建时间获取订单详情
     * @param int $status 订单状态(0:全部状态,2:待发货,3:已发货,5:已完成,8:维权中)
     * @param int $beginTime 订单创建时间起始时间戳(不带该字段则不按照时间做筛选)
     * @param int $endTime 订单创建时间终止时间戳(不带该字段则不按照时间做筛选)
     * @return array|bool
     */
    public function getOrderByFilter($status = 0, $beginTime = 0, $endTime = 0)
    {
        $data = [];
        if ($status) {
            $data['status'] = $status;
        }
        if ($beginTime && $endTime) {
            $data['begintime'] = $beginTime;
            $data['endtime'] = $endTime;
        }
        $url = 'https://api.weixin.qq.com/merchant/order/getbyfilter?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode($data)));
        return $this->checkReturn($return);
    }

    /**
     * 设置订单发货信息
     * @param string|array $json
     * @return array|bool
     */
    public function setOrderDelivery($json)
    {
        $json = is_string($json) ? $json : $this->jsonEncode($json);
        $url = 'https://api.weixin.qq.com/merchant/order/setdelivery?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $json));
        return $this->checkReturn($return);
    }

    /**
     * 关闭订单
     * @param string $orderId
     * @return array|bool
     */
    public function closeOrder($orderId)
    {
        $url = 'https://api.weixin.qq.com/merchant/order/close?access_token=' . $this->getAccessToken();
        $return = $this->jsonDecode($this->httpPost($url, $this->jsonEncode(['order_id'=>$orderId])));
        return $this->checkReturn($return);
    }

    /**
     * 上传图片(由于该接口文档不详细,所以该接口暂时不可使用,固定返回false)
     * @param string $fileName 图片文件名(带文件类型后缀)
     * @param string $fileStream 文件二进制流
     * @return array|bool
     */
    public function uploadImg($fileName, $fileStream)
    {
        return false;
        $url = 'https://api.weixin.qq.com/merchant/common/upload_img?access_token='.$this->getAccessToken().'&filename='.$fileName;
        $return = $this->jsonDecode($this->httpPost($url, $fileStream));
        return $this->checkReturn($return);
    }
}
