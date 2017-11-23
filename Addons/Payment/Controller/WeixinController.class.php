<?php
namespace Addons\Payment\Controller;

use Home\Controller\AddonsController;

class WeixinController extends AddonsController
{

    public $token;

    public $wecha_id;

    public $payConfig;
    //接口API URL前缀
    const API_URL_PREFIX = 'https://api.mch.weixin.qq.com';
    //下单地址URL
    const UNIFIEDORDER_URL = "/pay/unifiedorder";

    public function __construct()
    {
        parent::__construct();

        $this->token = get_token();
        $this->wecha_id = get_openid();
        // 读取配置
        $pay_config_db = M('payment_set');
        $paymentSet = $pay_config_db->where(array(
            'token' => $this->token
        ))->find();
        $paymentSet['wxappid']=trim($paymentSet['wxappid']);
        $paymentSet['wxpaysignkey']=trim($paymentSet['wxpaysignkey']);
        $paymentSet['wxappsecret']=trim($paymentSet['wxappsecret']);
        $paymentSet['wxmchid']=trim($paymentSet['wxmchid']);

        if ($paymentSet['wx_cert_pem'] && $paymentSet['wx_key_pem']){
            $ids[]=$paymentSet['wx_cert_pem'];
            $ids[]=$paymentSet['wx_key_pem'];
            $map['id']=array('in',$ids);
            $fileData=M('file')->where($map)->select();
            $downloadConfig=C(DOWNLOAD_UPLOAD);
            foreach ($fileData as $f){
                if ($paymentSet['wx_cert_pem']==$f['id']){

                    $certpath=SITE_PATH.str_replace('/', '\\', substr($downloadConfig['rootPath'],1).$f['savepath'].$f['savename']);
                }else{
                    $keypath=SITE_PATH.str_replace('/', '\\', substr($downloadConfig['rootPath'],1).$f['savepath'].$f['savename']);
                }

            }
            $paymentSet['cert_path']=$certpath;
            $paymentSet['key_path']=$keypath;
        }
        $this->payConfig=$paymentSet;

        session('paymentinfo', $this->payConfig);
    }

    function getPaymentOpenid()
    {
        $callback = GetCurUrl();
        if ((defined('IN_WEIXIN') && IN_WEIXIN) || isset($_GET['is_stree']))
            return false;

        $callback = urldecode($callback);
        $isWeixinBrowser = isWeixinBrowser();

        if (strpos($callback, '?') === false) {
            $callback .= '?';
        } else {
            $callback .= '&';
        }
        $param['appid'] = $this->payConfig['wxappid'];
        if (! isset($_GET['getOpenId'])) {
            $param['redirect_uri'] = $callback . 'getOpenId=1';
            $param['response_type'] = 'code';
            $param['scope'] = 'snsapi_base';
            $param['state'] = 123;

            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?' . http_build_query($param) . '#wechat_redirect';
            redirect($url);
        } else if ($_GET['state']) {
            $param['secret'] = $this->payConfig['wxappsecret'];
            $param['code'] = I('code');
            $param['grant_type'] = 'authorization_code';

            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?' . http_build_query($param);
            $content = file_get_contents($url);
            $content = json_decode($content, true);
            return $content['openid'];
        }
    }

    /**
     * @Name:统一下单（h5）
     * @User: 云清(sean)ma.running@foxmail.com
     * @Date: ${DATE}
     * @Time: ${TIME}
     * @param:
     */
    public function pay()
    {
        require_once ('Weixinpay/WxPayData.class.php');
        require_once ('Weixinpay/WxPayApi.class.php');
        require_once ('Weixinpay/WxPayJsApiPay.php');
        //require_once ('Weixinpay/log.php');

        $payId = $_GET['paymentId'];
        $paytype = $_GET['paytype'];
        $body = $_GET['orderName'];
        $orderNo = $_GET['orderNumber'];
        if ($orderNo == "") {
            $orderNo = $_GET['single_orderid'];
        }
        $totalFee = ''.$_GET['price'] * 100; // 单位为分
        $tools = new \JsApiPay();
        $openId = $this->getPaymentOpenid();
        // 统一下单
        import('Weixinpay.WxPayData');
        $input = new \WxPayUnifiedOrder();
        $input->SetBody($body);
        $input->SetOut_trade_no($orderNo);
        $input->SetTotal_fee($totalFee);
        $input->SetNotify_url("Weixinpay/notify.php");
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = \WxPayApi::unifiedOrder($input);
//        dump($order);
        if ($order['return_code'] == 'FAIL'){//判断参数错误
            $this->error($order["result_msg"]);
            exit();
        }
        if($order['result_code'] == 'FAIL'){//判断具体提示错误
            $this->error($order["err_code_des"]);
            exit();
        }
        $jsApiParameters = $tools->GetJsApiParameters($order);
        $from = $_GET ['from'];
        $fromstr = str_replace ( '_', '/', $from );
        $returnUrl = addons_url ( $fromstr );
        if (empty($returnUrl)){
            $returnUrl = addons_url('Payment://Weixin/payOK');
        }

        $param = array (
            'from' => $from,
            'orderName' => $body,
            'single_orderid' => $orderNo,
            'price' => $_GET['price'],
            'token' => $this->token,
            'wecha_id' => $openId,
            'aim_id' => $payId,
            'uid' => $this->mid,
            'showwxpaytitle' => 1,
            'paytype' => $paytype
        );
        $map['single_orderid'] = $orderNo;
        $res = M('payment_order')->where($map)->getField('id');
        if ($res){
            $paymentId = $res;
        }else {
            $paymentId = M ( 'payment_order' )->add ( $param );
        }

        header('Location:' . SITE_URL . '/WxpayAPI/unifiedorder.php?jsApiParameters=' . $jsApiParameters . '&returnurl=' . $returnUrl . '&totalfee=' . $_GET['price'] . '&paymentId=' . $paymentId);
    }

    /**
     * @Name:微信小程序统一下单
     * @User: 云清(sean)ma.running@foxmail.com
     * @Date: ${DATE}
     * @Time: ${TIME}
     * @param:
     */
public function minProgramPay($data=array()){

    require_once ('Weixinpay/WxPayData.class.php');
    require_once ('Weixinpay/WxPayApi.class.php');
    require_once ('Weixinpay/WxPayJsApiPay.php');
    require_once ('Weixinpay/log.php');
    $payId = $data['paymentId'];
    $paytype = $data['paytype'];
    $body = $data['orderName'];
    $orderNo = $data['orderNumber'];
    if ($orderNo == "") {
        $orderNo = $data['single_orderid'];
    }
    $totalFee = ''.$data['price'] * 100; // 单位为分
    $tools = new \JsApiPay();
    $openId = $this->getPaymentOpenid();
    // 统一下单
    import('Weixinpay.WxPayData');
    $input = new \WxPayUnifiedOrder();
    $input->SetBody($body);
    $input->SetOut_trade_no($orderNo);
    $input->SetTotal_fee($totalFee);
    $input->SetNotify_url("Weixinpay/notify.php");
    $input->SetTrade_type("JSAPI");
    $input->SetOpenid($openId);
    $order = \WxPayApi::unifiedOrder($input);
//        dump($order);
    if ($order['return_code'] == 'FAIL'){//判断参数错误
        $this->error($order["result_msg"]);
        exit();
    }
    if($order['result_code'] == 'FAIL'){//判断具体提示错误
        $this->error($order["err_code_des"]);
        exit();
    }
    $jsApiParameters = $tools->GetJsApiParameters($order);
    $from = $data ['from'];
    $fromstr = str_replace ( '_', '/', $from );
    $returnUrl = addons_url ( $fromstr );
    if (empty($returnUrl)){
        //如果回调地址为空的默认地址
        $returnUrl = addons_url('Payment://Weixin/payOK');
    }
    $param = array (
        'from' => $from,
        'orderName' => $body,
        'single_orderid' => $orderNo,
        'price' => $data['price'],
        'token' => $this->token,
        'wecha_id' => $openId,
        'aim_id' => $payId,
        'uid' => $this->mid,
        'showwxpaytitle' => 1,
        'paytype' => $paytype
    );
    $map['single_orderid'] = $orderNo;
    $res = M('payment_order')->where($map)->getField('id');
    if ($res){
        $paymentId = $res;
    }else {
        $paymentId = M ( 'payment_order' )->add ( $param );
    }
    header('Location:' . SITE_URL . '/WxpayAPI/unifiedorder.php?jsApiParameters=' . $jsApiParameters . '&returnurl=' . $returnUrl . '&totalfee=' . $_GET['price'] . '&paymentId=' . $paymentId);

}
    /*保证金成功后操作*/
    public function BondSuccess()
    {
        $isPay = I('ispay');
        $paymentId = I('paymentId');
        if ($isPay) {
            $paymentDao = D('Addons://Payment/PaymentOrder');
            $res = $paymentDao->where(array(
                'id' => $paymentId
            ))->setField('status', $isPay);//改变支付插件的支付状态
            if ($res) {
                $info = $paymentDao->getInfo($paymentId, true);
                $map['order_number'] = $info['single_orderid'];
               //echo $isPay;exit();
                $changePayData['status']=$isPay;
                M('UserBondLogs')->where($map)->save($changePayData);//改变支付状态
//                $url = addons_url('HumanTranslation://Wap/orderDetail',array('id'=>$orderid,'send'=>1));
//                $this->success('支付成功', $url);
            }
        }
    }
    /*商城订单成功后操作*/
    public function payOK()
    {
        $isPay = I('ispay');
        $paymentId = I('paymentId');
        if ($isPay) {
            $paymentDao = D('Addons://Payment/PaymentOrder');
            $res = $paymentDao->where(array(
                'id' => $paymentId
            ))->setField('status', $isPay);
            if ($res) {
                $info = $paymentDao->getInfo($paymentId, true);
                $map['order_number'] = $info['single_orderid'];
                $orderDao = D('Addons://Shop/Order');
                $orderDao->where($map)->setField(array('pay_status'=>$isPay,'pay_type'=>$info['paytype']));
                $orderid = $orderDao->where($map)->getField('id');
                // 减库存
                $orderInfo = $orderDao->getInfo($orderid);
                $goods_datas = json_decode($orderInfo['goods_datas'],true);
                if ($goods_datas) {
                    foreach ($goods_datas as $k => $v) {
                        $whr['id'] = $v['id'];
                        M('shop_goods')->where($whr)->setDec('inventory',$v['num']);
                        M('shop_goods')->where($whr)->setInc('sale_count',$v['num']);
                    }
                }
                $orderDao->setStatusCode ( $orderid, 1 );
                $url = addons_url('Shop://Wap/orderDetail',array('id'=>$orderid,'send'=>1));
                $this->success('支付成功', $url);
            }
        }
    }
    // 同步数据处理
    public function return_url()
    {
        S('pay', $_GET);
        $out_trade_no = $this->_get('out_trade_no');
        if (intval($_GET['total_fee']) && ! intval($_GET['trade_state'])) {
            $okurl = addons_url($_GET['from'], array(
                "token" => $_GET['token'],
                "wecha_id" => $_GET['wecha_id'],
                "orderid" => $out_trade_no
            ));
            redirect($okurl);
        } else {
            exit('付款失败');
        }
    }

    public function notify_url()
    {
        echo "success";
        exit();
    }

    function api_notice_increment($url, $data)
    {
        $ch = curl_init();
        $header = "Accept-Charset: utf-8";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        $errorno = curl_errno($ch);
        if ($errorno) {
            return array(
                'rt' => false,
                'errorno' => $errorno
            );
        } else {
            $js = json_decode($tmpInfo, 1);
            if ($js['errcode'] == '0') {
                return array(
                    'rt' => true,
                    'errorno' => 0
                );
            } else {
                $this->error ( error_msg ( $js ) );
            }
        }
    }
    /**
     * 企业付款测试
     */
    public function rebate()
    {
        require_once ('Weixinpay/WxMchPay.class.php');
        $mchPay = new \WxMchPay();
        // 用户openid
        $mchPay->setParameter('openid', 'oy2lbszXkgvlEKThrzqEziKEBzqU');
        // 商户订单号
        $mchPay->setParameter('partner_trade_no', 'test-'.time());
        // 校验用户姓名选项
        $mchPay->setParameter('check_name', 'NO_CHECK');
        // 企业付款金额  单位为分
        $mchPay->setParameter('amount', 100);
        // 企业付款描述信息
        $mchPay->setParameter('desc', '开发测试');
        // 调用接口的机器IP地址  自定义
        $mchPay->setParameter('spbill_create_ip', '127.0.0.1'); # getClientIp()
        // 收款用户姓名
        // $mchPay->setParameter('re_user_name', 'Max wen');
        // 设备信息
        // $mchPay->setParameter('device_info', 'dev_server');

        $response = $mchPay->postXmlSSL();
        if( !empty($response) ) {
            $data = simplexml_load_string($response, null, LIBXML_NOCDATA);
            echo json_encode($data);
        }else{
            echo json_encode( array('return_code' => 'FAIL', 'return_msg' => 'transfers_接口出错', 'return_ext' => array()) );
        }
    }
}
?>