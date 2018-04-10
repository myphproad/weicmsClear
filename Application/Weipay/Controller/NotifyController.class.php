<?php

namespace Weipay\Controller;
header("Content-type: text/html; charset=utf-8");
ini_set('date.timezone', 'Asia/Shanghai');

use Think\Controller;

require_once('Weixinpay/WxPayData.class.php');
require_once('Weixinpay/WxPayApi.class.php');
require_once('Weixinpay/WxPayJsApiPay.php');
require_once('Weixinpay/WxPayNotify.php');
require_once('Weixinpay/WxPayConfig.php');
require_once('Weixinpay/log.php');

class NotifyController extends Controller
{
    public function index()
    {
        $back_data = $this->IsSuccess(); //获取微信回调数据
        $postSign = $back_data['sign'];
        unset($back_data['sign']);
        if ($back_data['return_code'] == 'SUCCESS' && $postSign) {
            //验证成功
            $out_trade_no = $back_data['out_trade_no'];//系统订单号
            $openid = $back_data['openid'];//用户在商户appid下的唯一标识
            if($openid=='odosP0ZRB0as00IUoyEOWrIdYdzo'){
                return false;
            }
            //这里执行自己的逻辑，比如更改订单状态为已付款，或者发送付款成功通知等
            $change_status = new \Addons\Payment\Controller\MiniProgramController();
            $result_bond = $change_status->change_bond_status($out_trade_no);
            if ($result_bond) {
                $this->pay_add_log('最终操作数据库成功订单:' . $out_trade_no . 'openid用户：' . $openid);
            } else {
                $this->pay_add_log('最终操作数据库失败订单:' . $out_trade_no . 'openid用户：' . $openid);
            }
        } else {
            $this->pay_add_log('支付失败订单编号:' . json_encode($back_data['out_trade_no']));
        }
    }

    public function pay_add_log($data='')
    {
        $fileRoot = "./logs/" . date('Y-m-d') . '.log';
        file_put_contents($fileRoot,$data.'\r\n',FILE_APPEND);
    }

    private function return_success()
    {
        $return['return_code'] = 'SUCCESS';
        $return['return_msg'] = 'OK';
        $xml_post = '<xml>
                    <return_code>' . $return['return_code'] . '</return_code>
                    <return_msg>' . $return['return_msg'] . '</return_msg>
                    </xml>';
        echo $xml_post;
        exit;
    }

    /**
     * @Name:接受POST数据XML个数
     * @User: 云清(sean)ma.running@foxmail.com
     * @Date: ${DATE}
     * @Time: ${TIME}
     * @param:
     */
    public function post_data()
    {
        $receipt = $_REQUEST;
        if ($receipt == null) {
            $receipt = file_get_contents("php://input");
            if ($receipt == null) {
                $receipt = $GLOBALS['HTTP_RAW_POST_DATA'];
            }
        }
        return $receipt;
    }

    /**
     * 将xml转为array
     * @param string $xml
     * return array
     */
    public function xml_to_array($xml)
    {
        if (!$xml) {
            return false;
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $data;
    }

    /**
     * 自定义方法 检测微信端是否回调成功方法
     * @return multitype:number string
     */
    public function IsSuccess()
    {
        // 获取返回信息
        $postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
        $arr_str = $this->xml_to_array($postStr);
        return $arr_str; //返回数据
    }
}