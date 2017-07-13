<?php

namespace Addons\Payment\Model;
use Think\Model;

/**
 * Payment模型
 */
class PaymentOrderModel extends Model{
    /*
     *id为payment_order主键
     *  $update为true为更新 false新增
     *
     */
    function getInfo($id, $update = false, $data = array()) {
        $key = 'PaymentOrder_getInfo_' . $id;
        $info = S ( $key );//设置缓存标示
        if ($info === false || $update) {
            $info = ( array ) (count ( $data )==0 ? $this->find ( $id ) : $data);
            S ( $key, $info );
        }
        return $info;
    }
}
