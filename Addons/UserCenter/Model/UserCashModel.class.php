<?php

namespace Addons\UserCenter\Model;
use Think\Model;

/**
 * Cash模型
 */
class UserCashModel extends Model {
	protected $tableName = 'user_cash_logs';
	public $token;
	public $wecha_id;
	public $payConfig;
	public function __construct()
	{
		parent::__construct();
		header('Content-type: text/html; charset=UTF8');
		$this->token = get_token();
		$this->wecha_id = get_openid();
	}

	/*function getPaymentOpenid()
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
	}*/

	function getInfo($id, $update = false, $data = array()) {
		$key = 'cash_getInfo_' . $id;
		$info = S ( $key );
		if ($info === false || $update) {
			$result=M('TranslationOrder')->find($id);
			S ( $key, $result, 86400 );
		}
		return $info;
	}

    /**
     * @param $id
     * @param bool $update
     * @param array $data
     * @return mixed
     */
	function getSalaryInfo($id, $update = false, $data = array()) {
		$key = 'salary_getInfo_' . $id;
		$info = S ( $key );
		if ($info === false || $update) {
			$result=M('user_cash_logs')->find($id);
			S ( $key, $result, 86400 );
		}
        $info['title'] ="发放提现";
        $info['remark'] = $info["openid"].":发放提现:".$info["money"];
		return $info;
	}
    /**
     * @param $id
     * @param bool $update
     * @param array $data
     * @return mixed
     */
	function getBondInfo($id, $update = false, $data = array()) {
		$key = 'salary_getInfo_' . $id;
		$info = S ( $key );
		if ($info === false || $update) {
			$result=M('UserBondLogs')->find($id);
			S ( $key, $result, 86400 );
		}
        $info['title'] ="退保";
        $info['remark'] = $info["openid"].":退保:".$info["bond"];
		return $info;
	}
    /*
     * 发放工资
     *id:需要支付的订单信息id
     *$after_content_id:接受用户id
     *
     */
	function getCash($openid,$id = "") {
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
		$this->payConfig=$paymentSet;
		if ($paymentSet['wx_cert_pem'] && $paymentSet['wx_key_pem'] &&$paymentSet['wx_root_pem']){
			$ids[]=$paymentSet['wx_cert_pem'];
			$ids[]=$paymentSet['wx_key_pem'];
			$ids[]=$paymentSet['wx_root_pem'];
			$map['id']=array('in',$ids);
			$fileData=M('file')->where($map)->select();
			$downloadConfig=C(DOWNLOAD_UPLOAD);
			foreach ($fileData as $f){
				if ($paymentSet['wx_cert_pem']==$f['id']){
					$certpath=SITE_PATH.str_replace('\\\\', '/', substr($downloadConfig['rootPath'],1).$f['savepath'].$f['savename']);
				}
				if ($paymentSet['wx_root_pem']==$f['id']){
					$rootpath=SITE_PATH.str_replace('\\\\', '/',  substr($downloadConfig['rootPath'],1).$f['savepath'].$f['savename']);
				}
				if ($paymentSet['wx_key_pem']==$f['id']){
					$keypath=SITE_PATH.str_replace('\\\\', '/', substr($downloadConfig['rootPath'],1).$f['savepath'].$f['savename']);
				}
			}
			$paymentSet['cert_path']=$certpath;
			$paymentSet['key_path']=$keypath;
			$paymentSet['root_path']=$rootpath;
		}
		$info = $this->getSalaryInfo ($id, true );
		$amount=$info['money'];//金额（以分为单位，必须大于100）
		if ($amount <= 0) {
			$returnData ['msg_code'] = 0;
			$returnData ['msg'] = '您的结算佣金出现问题，请联系官方';
			return $returnData;
		}

		// 商户和公众号信息
		$data ['mchid'] = $paymentSet ['wxmchid']; // 微信支付分配的商户号
		$data ['partner_trade_no'] = $paymentSet ['wxmchid'] . date ( Ymd ) . $this->getRandStr (); // 商户订单号（每个订单号必须唯一）组成： mch_id+yyyymmdd+10位一天内不能重复的数字。接口根据商户订单号支持重入， 如出现超时可再调用。
		$data ['mch_appid'] = $paymentSet ['wxappid']; // 商户appid，如：wx9e088eb8b3152ae2
//		$data ['openid'] = getPaymentOpenid (trim($paymentSet['wxappid']),trim($paymentSet['wxappsecret'])); // 接受现金的用户
//		$data ['openid'] = 'odosP0YD9juZDpPPJoN69IEPv-pQ'; // 接受现金的sean 用户
		$data ['openid'] = $openid; //
		$data ['nonce_str'] = uniqid (); // 随机字符串，不长于32位
//		$data ['nonce_str'] = '58e47960e83a6'; // 随机字符串，不长于32位
		$data ['amount'] = $amount;//企业付款金额，单位为分
		$data ['desc'] = $info ['title'];
		$data ['spbill_create_ip'] = $_SERVER ['SERVER_ADDR'];
		$data ['check_name'] = NO_CHECK;//不校验真实姓名
//		$data ['re_user_name'] = $info ['title'];
		$data ['re_user_name'] = '测试';
		$data ['sign'] = $this->getSign( $data,$paymentSet ['wxpaysignkey']);
//		mch_id商户号  mch_appid公众账号appid  device_info设备号[可选]  随机字符串nonce_str 签名sign 商户订单号partner_trade_no
//		用户openid 校验用户姓名选项	check_name 收款用户姓名[可选]	校验用户姓名选项re_user_name    金额	amount 企业付款描述信息	desc Ip地址	spbill_create_ip
		$vars = "<xml>
		<mch_appid>{$data ['mch_appid']}</mch_appid>
		<mchid>{$data ['mchid']}</mchid>
		<nonce_str>{$data ['nonce_str']}</nonce_str>
		<partner_trade_no>{$data ['partner_trade_no']}</partner_trade_no>
		<openid>{$data ['openid']}</openid>
		<check_name>{$data ['check_name']}</check_name>
		<re_user_name>{$data ['re_user_name']}</re_user_name>
		<amount>{$data ['amount']}</amount>
		<desc>{$data['desc']}</desc>
		<spbill_create_ip>{$data ['spbill_create_ip']}</spbill_create_ip>
		<sign>{$data ['sign']}</sign>
		</xml>";
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';//企业付款api
		// 获取证书路径
		$paymentSet['cert_path']=$certpath;
		$paymentSet['key_path']=$keypath;
		$paymentSet['root_path']=$rootpath;
		$res = $this->curl_post_ssl ( $url, $vars, $paymentSet['cert_path'], $paymentSet['key_path'],$paymentSet['root_path']);

		if ($res ['result_code'] == 'FAIL') {
			$returnData ['msg_code'] = 0;
			$returnData ['msg'] = $res ['err_code_des'] . ', 错误码： ' . $res ['err_code'];
			return $returnData;
		}
		// 记录日志
        $recode ['single_orderid'] = $id;
		$recode ['openid'] = $data ['openid'];
		$recode ['price'] = $amount;
		$recode ['ctime'] = date("Y:m:d H:i:s",time());
		$recode ['uid'] = $info['user_id'];
        $recode ['remark'] = $info['remark'];
        $recode ['paytype'] = 2;
		$recode ['order_data'] = json_encode ( $data );
		M ( 'paymentOrder' )->add ( $recode );
		$this->getSalaryInfo ( $id, true );
		$returnData ['msg_code'] = 1;
		$returnData ['msg'] = '工资发放成功';
		return $returnData;
	}
    /*
    * 发放保证金
    *id:需要支付的订单信息id
    *$after_content_id:接受用户id
    *
    */
    function getBonds($openid,$id = "") {
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
        $this->payConfig=$paymentSet;
        if ($paymentSet['wx_cert_pem'] && $paymentSet['wx_key_pem'] &&$paymentSet['wx_root_pem']){
            $ids[]=$paymentSet['wx_cert_pem'];
            $ids[]=$paymentSet['wx_key_pem'];
            $ids[]=$paymentSet['wx_root_pem'];
            $map['id']=array('in',$ids);
            $fileData=M('file')->where($map)->select();
            $downloadConfig=C(DOWNLOAD_UPLOAD);
            foreach ($fileData as $f){
                if ($paymentSet['wx_cert_pem']==$f['id']){
                    $certpath=SITE_PATH.str_replace('\\\\', '/', substr($downloadConfig['rootPath'],1).$f['savepath'].$f['savename']);
                }
                if ($paymentSet['wx_root_pem']==$f['id']){
                    $rootpath=SITE_PATH.str_replace('\\\\', '/',  substr($downloadConfig['rootPath'],1).$f['savepath'].$f['savename']);
                }
                if ($paymentSet['wx_key_pem']==$f['id']){
                    $keypath=SITE_PATH.str_replace('\\\\', '/', substr($downloadConfig['rootPath'],1).$f['savepath'].$f['savename']);
                }
            }
            $paymentSet['cert_path']=$certpath;
            $paymentSet['key_path']=$keypath;
            $paymentSet['root_path']=$rootpath;
        }
        $info = $this->getBondInfo ($id, true );
        $amount=$info['money'];//金额（以分为单位，必须大于100）
        if ($amount <= 0) {
            $returnData ['msg_code'] = 0;
            $returnData ['msg'] = '您的结算佣金出现问题，请联系官方';
            return $returnData;
        }

        // 商户和公众号信息
        $data ['mchid'] = $paymentSet ['wxmchid']; // 微信支付分配的商户号
        $data ['partner_trade_no'] = $paymentSet ['wxmchid'] . date ( Ymd ) . $this->getRandStr (); // 商户订单号（每个订单号必须唯一）组成： mch_id+yyyymmdd+10位一天内不能重复的数字。接口根据商户订单号支持重入， 如出现超时可再调用。
        $data ['mch_appid'] = $paymentSet ['wxappid']; // 商户appid，如：wx9e088eb8b3152ae2
//		$data ['openid'] = getPaymentOpenid (trim($paymentSet['wxappid']),trim($paymentSet['wxappsecret'])); // 接受现金的用户
//		$data ['openid'] = 'odosP0YD9juZDpPPJoN69IEPv-pQ'; // 接受现金的sean 用户
        $data ['openid'] = $openid; //
        $data ['nonce_str'] = uniqid (); // 随机字符串，不长于32位
//		$data ['nonce_str'] = '58e47960e83a6'; // 随机字符串，不长于32位
        $data ['amount'] = $amount;//企业付款金额，单位为分
        $data ['desc'] = $info ['title'];
        $data ['spbill_create_ip'] = $_SERVER ['SERVER_ADDR'];
        $data ['check_name'] = NO_CHECK;//不校验真实姓名
//		$data ['re_user_name'] = $info ['title'];
        $data ['re_user_name'] = '测试';
        $data ['sign'] = $this->getSign( $data,$paymentSet ['wxpaysignkey']);
//		mch_id商户号  mch_appid公众账号appid  device_info设备号[可选]  随机字符串nonce_str 签名sign 商户订单号partner_trade_no
//		用户openid 校验用户姓名选项	check_name 收款用户姓名[可选]	校验用户姓名选项re_user_name    金额	amount 企业付款描述信息	desc Ip地址	spbill_create_ip
        $vars = "<xml>
		<mch_appid>{$data ['mch_appid']}</mch_appid>
		<mchid>{$data ['mchid']}</mchid>
		<nonce_str>{$data ['nonce_str']}</nonce_str>
		<partner_trade_no>{$data ['partner_trade_no']}</partner_trade_no>
		<openid>{$data ['openid']}</openid>
		<check_name>{$data ['check_name']}</check_name>
		<re_user_name>{$data ['re_user_name']}</re_user_name>
		<amount>{$data ['amount']}</amount>
		<desc>{$data['desc']}</desc>
		<spbill_create_ip>{$data ['spbill_create_ip']}</spbill_create_ip>
		<sign>{$data ['sign']}</sign>
		</xml>";
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';//企业付款api
        // 获取证书路径
        $paymentSet['cert_path']=$certpath;
        $paymentSet['key_path']=$keypath;
        $paymentSet['root_path']=$rootpath;
        $res = $this->curl_post_ssl ( $url, $vars, $paymentSet['cert_path'], $paymentSet['key_path'],$paymentSet['root_path']);

        if ($res ['result_code'] == 'FAIL') {
            $returnData ['msg_code'] = 0;
            $returnData ['msg'] = $res ['err_code_des'] . ', 错误码： ' . $res ['err_code'];
            return $returnData;
        }
        // 记录日志
        $recode ['single_orderid'] = $id;
        $recode ['openid'] = $data ['openid'];
        $recode ['price'] = $amount;
        $recode ['ctime'] = date("Y:m:d H:i:s",time());
        $recode ['uid'] = $info['user_id'];
        $recode ['remark'] = $info['remark'];
        $recode ['paytype'] = 3;
        $recode ['order_data'] = json_encode ( $data );
        M ( 'paymentOrder' )->add ( $recode );
        $this->getBondInfo ( $id, true );
        $returnData ['msg_code'] = 1;
        $returnData ['msg'] = '退保成功';
        return $returnData;
    }
	function curl_post_ssl($url, $vars, $cert_dir = '', $key_dir = '', $root_dir = '') {
		$ch = curl_init ();
		// 超时时间
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 30 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		// 这里设置代理，如果有的话
		// curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
		// curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
		curl_setopt ( $ch, CURLOPT_URL, $url );
		//因为微信红包在使用过程中需要验证服务器和域名，故需要设置下面两行
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // 只信任CA颁布的证书
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名，并且是否与提供的主机名匹配

		// 以下两种方式需选择一种

		// 第一种方法，cert 与 key 分别属于两个.pem文件
		// 默认格式为PEM，可以注释
		curl_setopt ( $ch, CURLOPT_SSLCERTTYPE, 'PEM' );
		curl_setopt ( $ch, CURLOPT_SSLCERT, $cert_dir );
		// 默认格式为PEM，可以注释
		curl_setopt ( $ch, CURLOPT_SSLKEYTYPE, 'PEM' );
		curl_setopt ( $ch, CURLOPT_SSLKEY, $key_dir );
		/*微信支付的api ca证书*/
		curl_setopt($ch,CURLOPT_CAINFO,$root_dir);// CA根证书（用来验证的网站证书是否是CA颁布）

		// 第二种方式，两个文件合成一个.pem文件
		// curl_setopt ( $ch, CURLOPT_SSLCERT, getcwd () . '/all.pem' );

		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $vars );
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$content = curl_exec ( $ch );

//		dump($content);
		if ($content) {
			$data = new \SimpleXMLElement ( $content );
			foreach ( $data as $key => $value ) {
				$msg [$key] = $value;
			}
		} else {
			$msg ['return_code'] = 'FAIL';
			$msg ['return_msg'] = "请求失败, 失败编号: " . curl_errno ( $ch );
		}
		curl_close ( $ch );
		return $msg;
	}
	function obj2array($xml) {
	}
	function getRandStr() {
		$arr = array (
				'A',
				'B',
				'C',
				'D',
				'E',
				'F',
				'G',
				'H',
				'I',
				'J',
				'K',
				'L',
				'M',
				'N',
				'O',
				'P',
				'Q',
				'R',
				'S',
				'T',
				'U',
				'V',
				'W',
				'X',
				'Y',
				'Z'
		);
		$key = array_rand ( $arr );
		return substr ( time (), - 5 ) . substr ( microtime (), 2, 4 ) . $arr [$key];
	}
	/**
	 * 	作用：格式化参数，签名过程需要使用
	 */
	function formatBizQueryParaMap($paraMap, $urlencode)
	{
//		var_dump($paraMap);//die;
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
			if($urlencode)
			{
				$v = urlencode($v);
			}
			//$buff .= strtolower($k) . "=" . $v . "&";
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar;
		if (strlen($buff) > 0)
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
//		var_dump($reqPar);//die;
		return $reqPar;
	}

	/**
	 * 	作用：生成签名
	 */
	function getSign($Obj,$partner_key = '')
	{
		foreach ($Obj as $k => $v)
		{
			$Parameters[$k] = $v;
		}
		//签名步骤一：按字典序排序参数
		ksort($Parameters);
		$String = $this->formatBizQueryParaMap($Parameters, false);
//		echo '【string1】'.$String.'</br>';
		//签名步骤二：在string后加入KEY
		$String = $String."&key=".$partner_key;
//		echo "【string2】".$String."</br>";
		//签名步骤三：MD5加密
		$String = md5($String);
//		echo "【string3】 ".$String."</br>";
		//签名步骤四：所有字符转为大写
		$result_ = strtoupper($String);
//		echo "【result】 ".$result_."</br>";
		return $result_;
	}
}
