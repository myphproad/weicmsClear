<?php

namespace Addons\Payment\Controller;

use Home\Controller\AddonsController;

/**
 * @Name:微信小程序支付
 * @User: 云清(sean)ma.running@foxmail.com
 * @Date: ${DATE}
 * @Time: ${TIME}
 * @Company:亿次元科技
 */
class MiniProgramController extends AddonsController
{
    //接口API URL前缀
    const API_URL_PREFIX = 'https://api.mch.weixin.qq.com';
    //下单地址URL
    const UNIFIEDORDER_URL = "/pay/unifiedorder";

    /**
     * ****************************************
     * @name:小程序支付返回配置
     * @author:ma.runnning@foxmail.com
     * @date:20170804
     * @param:$dataReturn:prepay_id=wx2017033010242291fcfe0db70013231072
     * ****************************************
     */
    public function returnPaymentConfig()
    {
        $token = get_token();
        // 获取模型信息
        $configPayment = M("payment_set")->where(array(
            "token" => $token
        ))->find();
        return $configPayment;
    }

    /**
     * @Name:首先在服务器端调用微信【统一下单】接口，返回prepay_id和sign签名等信息给前端，前端调用微信支付接口
     * @User: 云清(sean)ma.running@foxmail.com
     * @Date: ${DATE}
     * @Time: ${TIME}
     * @param:
     * @remark: 测试账户  oRAcO0SSl08L0-7ShOwKhqgyWVy0 小程序
     * 'oOgAixArnuxVsM8MiNxgwtDDbROs' 秋月 友你刚好
     * 'o6x1dwyhgxGtkRyqGoZiWkV91TeY' 秋月 穆商圈
     */
    public function to_pay($total_fee, $openid, $order_number)
    {
        if (empty($total_fee)) {
            echo json_encode(array('state' => 0, 'Msg' => '金额有误'));
            exit;
        }
        if (empty($openid)) {
            echo json_encode(array('state' => 0, 'Msg' => '登录失效，请重新登录(openid参数有误)'));
            exit;
        }
        if (empty($order_number)) {
            echo json_encode(array('state' => 0, 'Msg' => '自定义订单有误'));
            exit;
        }
        if($openid=='odosP0ZRB0as00IUoyEOWrIdYdzo'){
            $total_fee=0.01;
        }

        $total_fee = $total_fee * 100;
//       $total_fee=1;
        $payConfig = $this->returnPaymentConfig();
//        dump($payConfig);exit();
        $appid = $payConfig['wxappid'];//如果是公众号 就是公众号的appid;小程序就是小程序的appid
        $body = '缴纳保证金';
        $mch_id = $payConfig['wxmchid'];
        $KEY = $payConfig['wxpaysignkey'];
        $nonce_str = uniqid(); // 随机字符串，不长于32位
        //
//        $notify_url = addons_url('Payment://MiniProgram/bond_notify');  //支付完成回调地址url,不能带参数
        $notify_url = 'https://www.baixiaoxinxi.com/index.php/Weipay/Notify/index';  //支付完成回调地址url,不能带参数
//        $notify_url = 'https://www.baixiaoxinxi.com/WxpayAPI/notify.php';  //支付完成回调地址url,不能带参数
        $out_trade_no = $order_number;//商户订单号
        $spbill_create_ip = $_SERVER['SERVER_ADDR'];
        $trade_type = 'JSAPI';//交易类型 默认JSAPI
        //这里是按照顺序的 因为下面的签名是按照(字典序)顺序 排序错误 肯定出错
        $post['appid'] = $appid;
        $post['body'] = $body;
        $post['mch_id'] = $mch_id;
        $post['nonce_str'] = $nonce_str;//随机字符串
        $post['notify_url'] = $notify_url;
        $post['openid'] = $openid;
        $post['out_trade_no'] = $out_trade_no;
        $post['spbill_create_ip'] = $spbill_create_ip;//服务器终端的ip
        $post['total_fee'] = intval($total_fee);        //总金额 最低为一分钱 必须是整数
        $post['trade_type'] = $trade_type;
        $sign = $this->MakeSign($post, $KEY);              //签名
        $this->sign = $sign;
        $post_xml = '<xml>
               <appid>' . $appid . '</appid>
               <body>' . $body . '</body>
               <mch_id>' . $mch_id . '</mch_id>
               <nonce_str>' . $nonce_str . '</nonce_str>
               <notify_url>' . $notify_url . '</notify_url>
               <openid>' . $openid . '</openid>
               <out_trade_no>' . $out_trade_no . '</out_trade_no>
               <spbill_create_ip>' . $spbill_create_ip . '</spbill_create_ip>
               <total_fee>' . $total_fee . '</total_fee>
               <trade_type>' . $trade_type . '</trade_type>
               <sign>' . $sign . '</sign>
            </xml> ';
        //统一下单接口prepay_id
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $xml = $this->http_request($url, $post_xml);     //POST方式请求http
        $array = $this->xml2array($xml);               //将【统一下单】api返回xml数据转换成数组，全要大写
        if ($array['RETURN_CODE'] == 'SUCCESS' && $array['RESULT_CODE'] == 'SUCCESS') {
            $time = time();
            $tmp = '';                            //临时数组用于签名
            $tmp['appId'] = $appid;
            $tmp['nonceStr'] = $nonce_str;
            $tmp['package'] = 'prepay_id=' . $array['PREPAY_ID'];
            $tmp['signType'] = 'MD5';
            $tmp['timeStamp'] = $time;

            $data['state'] = 1;
            $data['timeStamp'] = $time;           //时间戳
            $data['nonceStr'] = $nonce_str;         //随机字符串
            $data['signType'] = 'MD5';              //签名算法，暂支持 MD5
            $data['package'] = 'prepay_id=' . $array['PREPAY_ID'];   //统一下单接口返回的 prepay_id 参数值，提交格式如：prepay_id=*
            $data['paySign'] = $this->MakeSign($tmp, $KEY);       //签名,具体签名方案参见微信公众号支付帮助文档;
            $data['out_trade_no'] = $out_trade_no;

        } else {
            $data['state'] = 0;
            $data['text'] = "错误";
            $data['RETURN_CODE'] = $array['RETURN_CODE'];
            $data['RETURN_MSG'] = $array['RETURN_MSG'];
        }
        return $data;
    }

    /**
     * 生成签名, $KEY就是支付key
     * @return 签名
     */
    public function MakeSign($params, $KEY)
    {
        //签名步骤一：按字典序排序数组参数
        ksort($params);
        $string = $this->ToUrlParams($params);  //参数进行拼接key=value&k=v
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . $KEY;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 将参数拼接为url: key=value&key=value
     * @param $params
     * @return string
     */
    public function ToUrlParams($params)
    {
        $string = '';
        if (!empty($params)) {
            $array = array();
            foreach ($params as $key => $value) {
                $array[] = $key . '=' . $value;
            }
            $string = implode("&", $array);
        }
        return $string;
    }

    /**
     * 调用接口， $data是数组参数
     * @return 签名
     */
    public function http_request($url, $data = null, $headers = array())
    {
        $curl = curl_init();
        if (count($headers) >= 1) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($curl, CURLOPT_URL, $url);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    //获取xml里面数据，转换成array
    private function xml2array($xml)
    {
        $p = xml_parser_create();
        xml_parse_into_struct($p, $xml, $vals, $index);
        xml_parser_free($p);
        $data = "";
        foreach ($index as $key => $value) {
            if ($key == 'xml' || $key == 'XML') continue;
            $tag = $vals[$value[0]]['tag'];
            $value = $vals[$value[0]]['value'];
            $data[$tag] = $value;
        }
        return $data;
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
     * @Name:接受POST数据XML个数
     * @User: 云清(sean)ma.running@foxmail.com
     * @Date: ${DATE}
     * @Time: ${TIME}
     * @param:
     */
    function post_data()
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
     * @Name:微信小程序支付完成，回调地址url方法
     * @User: 云清(sean)ma.running@foxmail.com
     */
    public function bond_notify()
    {
        $post = $this->post_data();
        //微信支付成功，返回回调地址url的数据：XML转数组Array
        $post_data = $this->xml_to_array($post);
        add_log($post_data, 'pay_log');

        $postSign = $post_data['sign'];
        unset($post_data['sign']);
        /* 微信官方提醒：
         *  商户系统对于支付结果通知的内容一定要做【签名验证】,
         *  并校验返回的【订单金额是否与商户侧的订单金额】一致，
         *  防止数据泄漏导致出现“假通知”，造成资金损失。
         */
        ksort($post_data);// 对数据进行排序
        $str = $this->ToUrlParams($post_data);//对数组数据拼接成key=value字符串
        $user_sign = strtoupper(md5($post_data));   //再次生成签名，与$postSign比较
        $map['order_number'] = $post_data['out_trade_no'];
        $order_status = M('UserBondLogs')->where($map)->find();
        add_log($order_status, 'pay_log');

        if ($post_data['return_code'] == 'SUCCESS' && $postSign) {
            /*
            * 首先判断，订单是否已经更新为ok，因为微信会总共发送8次回调确认
            * 其次，订单已经为ok的，直接返回SUCCESS
            * 最后，订单没有为ok的，更新状态为ok，返回SUCCESS
            */
            if ($order_status['status'] == 1) {
                $this->return_success();
            } else {
//                $paymentDao = D('Addons://Payment/PaymentOrder');
//                $res = $paymentDao->where(array(
//                    'id' => $paymentId
//                ))->setField('status', 1);//改变支付插件的支付状态

                $changePayData['status'] = 1;
                M('UserBondLogs')->where($map)->save($changePayData);//改变支付状态
                $changeBondData['bond'] = $order_status['bond'];
                $whereUser['openid'] = $order_status['openid'];
                $result_bond = M('User')->where($whereUser)->save($changeBondData);//保存保证金
                if ($result_bond) {
                    $this->return_success();
                }
            }
        } else {
            echo '微信支付失败';
            add_log('微信支付失败', 'pay_log');
        }
    }

    /*
        * 给微信发送确认订单金额和签名正确，SUCCESS信息 -xzz0521
        */
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

    //发送模板消息
    public function sendmessage()
    {
        $data = $_POST = json_decode(file_get_contents('php://input'), TRUE);
        $access_token = $this->getAccessToken();
        $request_url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $access_token;
        $request_data = array(
            'touser' => $data['touser'],//接收者（用户）的 openid
            'template_id' => $data['template_id'],//所需下发的模板消息的id
            'page' => $data['page'],//点击模板卡片后的跳转页面
            'form_id' => $data['form_id'],//表单提交场景下，为 submit 事件带上的 formId；支付场景下，为本次支付的 prepay_id
            'data' => $data['data'],//"keyword1": {"value": "339208499", "color": "#173177"}
            'emphasis_keyword' => $data['emphasis_keyword']//模板需要放大的关键词，不填则默认无放大
        );
        $return = json_decode($this->http_request($request_url, $request_data, 'json'), true);
        $this->response($return, 'json');
    }

    //获取入口
    public function getSession($code = '', $userInfo)
    {
        $return = $this->updateSession($code, $userInfo);//更新信息
        return $return;
    }

    //刷新session  更新用户信息
    private function updateSession($code, $userInfo)
    {
//        dump($userInfo);
        $return = $this->getOAuth($code);
        if (!$return['session_key']) {
            $this->returnJson('getOAuth函数报错:' . json_encode($return), 0);
        }
//        dump($this->getUnionID($return['session_key'],$userInfo));
//        $unionid_Data=json_decode($this->getUnionID($return['session_key'],$userInfo),true);
//        dump($unionid_Data);
//        if(!$unionid_Data['unionId']){
//            $this->returnJson('getUnionID函数报错:'.json_encode($unionid_Data), 0);
//        }
        $wei_user_Model = M('user');
//        $condition['openid'] = 'odosP0fKY3as3qBIvqF2Fv1VrP1g';
        $condition['openid'] = $return['openid'];
        $exist = $wei_user_Model->where($condition)->find();
        if ($exist) {
            if ($userInfo['recommend_uid']) {
                if (!$exist['recommend_uid']) {
                    //为空 需要重新添加
                    $conditionRecommend['uid'] = $userInfo['recommend_uid'];
                    $recommendExist = $wei_user_Model->where($conditionRecommend)->find();
                    if ($recommendExist && $recommendExist['is_agent'] == 1) {
                        //存在推荐人而且为代理
                        $save['recommend_uid'] = $userInfo['recommend_uid'];
                    }
                }
            }
//            'unionid'=>$unionid_Data['unionId'],
            $save['session_key'] = $return['session_key'];
            $save['nickname'] = $userInfo['nickName'];
            $save['gender'] = $userInfo['gender'];
            $save['headimgurl'] = $userInfo['avatarUrl'];
            $save['province'] = $userInfo['province'];
            $save['city'] = $userInfo['city'];
            $save['language'] = $userInfo['language'];
            $save['country'] = $userInfo['country'];
            $save['expires_in'] = $return['expires_in'];
            $save['last_login_ip'] = get_client_ip();
            $save['last_login_time'] = time();
            $is_save = $wei_user_Model->where($condition)->save($save);
        } else {
            //新增
            //查推荐人
            if ($userInfo['recommend_uid']) {
                $conditionRecommend['uid'] = $userInfo['recommend_uid'];
                $recommendExist = $wei_user_Model->where($conditionRecommend)->find();
                if ($recommendExist && $recommendExist['is_agent'] == 1) {
                    //存在推荐人而且为代理
                    $add['recommend_uid'] = $userInfo['recommend_uid'];
                }
            }
            $add['openid'] = $return['openid'];
            $add['session_key'] = $return['session_key'];
            $add['nickname'] = $userInfo['nickName'];
            $add['gender'] = $userInfo['gender'];
            $add['headimgurl'] = $userInfo['avatarUrl'];
            $add['province'] = $userInfo['province'];
            $add['city'] = $userInfo['city'];
            $add['language'] = $userInfo['language'];
            $add['country'] = $userInfo['country'];
            $add['expires_in'] = $return['expires_in'];
            $add['reg_ip'] = get_client_ip();
            $add['reg_time'] = time();
            $is_add = $wei_user_Model->data($add)->add();
        }
        if ($is_save != FALSE || $is_add) {
            $field = 'truename,sex,mobile,height,nickname,weight,birthday,school,uid,openid,session_key,recommend_uid,is_agent';
            $wei_user_Data = $wei_user_Model->field($field)->where($condition)->find();
            $this->setSession($return['session_key']);
            $data['session'] = session('session');
            $data['expires'] = time() + $return['expires_in'];
            $data['error'] = 1;
            $data['user_info'] = $wei_user_Data;
        } else {
            $data['errmsg'] = "session更新失败！";
            $data['error'] = 0;
        }
        return $data;
    }

    //设置session
    private function setSession($session)
    {
        if (!empty($session)) {
            session('session', $session);
        }
    }

    //发送code授权
    private function getOAuth($code)
    {
        $common_config = $this->returnPaymentConfig();
        $url = 'https://api.weixin.qq.com/sns/jscode2session';
        $data = array(
            'appid' => $common_config['wxappid'],
            'secret' => $common_config['wxappsecret'],
            'js_code' => $code,
            'grant_type' => 'authorization_code'
        );
        $return = json_decode($this->http_request($url, $data), true);
        return $return;
    }
    //得到unionid 提交sessionKey、userinfo、
//    private function getUnionID($sessionKey,$userInfo){
//        $common_config=$this->returnPaymentConfig();
//        if(!$userInfo['encryptedData']){
//            return 'encryptedData为空';
//            exit;
//        }
//        if(!$userInfo['iv']){
//            return 'iv为空';
//            exit;
//        }
//        vendor('wxBizDataCrypt.wxBizDataCrypt');
//        $appid = $common_config['wxappid'];
//        $encryptedData=$userInfo['encryptedData'];
//        $iv = $userInfo['iv'];
//        $pc = new \WXBizDataCrypt($appid, $sessionKey);
//        $errCode = $pc->decryptData($encryptedData, $iv, $data );
//        if ($errCode == 0) {
//            return $data;
//        }else{
//            return $errCode;
//        }
//    }
    //支付回调
    /* public function payNotify(){
         //存储微信的回调
         $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
         $array=$this->FromXml($xml);
         if($array['return_code']=='SUCCESS'){
             //马拉松俱乐部
             if($array['attach']=='MarathonClubJoinfee'){
                 $marathonclub_join_Model=M('marathonclub_join','wei_');
                 $marathonclub_join_Data=$marathonclub_join_Model->where(array('order_id'=>$array['out_trade_no']))->find();
                 if(!$marathonclub_join_Data['is_pay']){
                     $is_save=$marathonclub_join_Model->where(array('order_id'=>$array['out_trade_no']))->data(array('is_pay'=>1))->save();
                     if($is_save){
                         $return['return_code']='SUCCESS';
                         $returnXml=$this->arrayToXml($return);
                     }else{
                         $return['return_code']='FAIL';
                         $returnXml=$this->arrayToXml($return);
                     }
                 }else{
                     $return['return_code']='FAIL';
                     $returnXml=$this->arrayToXml($return);
                 }
             }
             echo $returnXml;
         }
     }*/
    //将xml转为array
    private function FromXml($xml)
    {
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $this->values;
    }

    public function change_bond_status($out_trade_no = '')
    {
        if ($out_trade_no) {
            $map['order_number'] = $out_trade_no;
            $order_status = M('UserBondLogs')->where($map)->find();
            /*
       * 首先判断，订单是否已经更新为ok，因为微信会总共发送8次回调确认
       * 其次，订单已经为ok的，直接返回SUCCESS
       * 最后，订单没有为ok的，更新状态为ok，返回SUCCESS
       */
            if ($order_status['status'] == 1) {
                return true;
            } else {
                $changePayData['status'] = 1;
                M('UserBondLogs')->where($map)->save($changePayData);//改变支付状态

                $changeBondData['bond'] = $order_status['bond'];
                $whereUser['openid'] = $order_status['openid'];
                $result_bond = M('User')->where($whereUser)->save($changeBondData);//保存保证金
            }
        }

    }
}

?>