<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Home\Controller;

use Think\Controller;

/**
 * 扩展控制器
 * 用于调度各个扩展的URL访问需求
 */
class AddonsController extends Controller {
	protected $addons = null;
	protected $model;
	function _initialize() {
		
		$token = get_token ();
		$param = array (
				'lists',
				'config',
				'nulldeal' 
		);
		
		C ( 'EDITOR_UPLOAD.rootPath', './Uploads/Editor/' . $token . '/' );
		
		if ($GLOBALS ['is_wap']) {
			// 默认错误跳转对应的模板文件s
			C ( 'TMPL_ACTION_ERROR', 'Addons:dispatch_jump_mobile' );
			// 默认成功跳转对应的模板文件
			C ( 'TMPL_ACTION_SUCCESS', 'Addons:dispatch_jump_mobile' );
		} else {
			$this->_nav ();
		}
	}
	public function execute($_addons = null, $_controller = null, $_action = null) {
	}
	public function plugin($_addons = null, $_controller = null, $_action = null) {
	}
	function _nav() {
		$addon = D ( 'Home/Addons' )->getInfoByName ( _ADDONS );
		
		$nav = array ();
		if ($addon ['has_adminlist']) {
			$res ['title'] = $addon ['title'];
			$res ['url'] = U ( 'lists' );
			$res ['class'] = _ACTION == 'lists' ? 'current' : '';
			$nav [] = $res;
		}
		if (file_exists ( ONETHINK_ADDON_PATH . _ADDONS . '/config.php' )) {
			$res ['title'] = '功能配置';
			$res ['url'] = U ( 'config' );
			$res ['class'] = _ACTION == 'config' ? 'current' : '';
			$nav [] = $res;
		}
		if (empty ( $nav ) && _ACTION != 'nulldeal') {
			U ( 'nulldeal', '', true );
		}
		$this->assign ( 'nav', $nav );
		
		return $nav;
	}
	/**
	 * 重写模板显示 调用内置的模板引擎显示方法，
	 *
	 * @access protected
	 * @param string $templateFile
	 *        	指定要调用的模板文件
	 *        	默认为空 由系统自动定位模板文件
	 *        	支持格式: 空, index, UserCenter/index 和 完整的地址
	 * @param string $charset
	 *        	输出编码
	 * @param string $contentType
	 *        	输出类型
	 * @param string $content
	 *        	输出内容
	 * @param string $prefix
	 *        	模板缓存前缀
	 * @return void
	 */
	protected function display($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = '') {
		$templateFile = $this->getAddonTemplate ( $templateFile );
		$this->view->display ( $templateFile, $charset, $contentType, $content, $prefix );
	}
	function getAddonTemplate($templateFile = '') {
		if (file_exists ( $templateFile )) {
			return $templateFile;
		}
		$type = is_dir ( ONETHINK_PLUGIN_PATH . _ADDONS ) ? 'Plugins' : 'Addons';
		// dump ( $templateFile );
		$oldFile = $templateFile;
		if (empty ( $templateFile )) {
			$templateFile = T ( $type . '://' . _ADDONS . '@' . _CONTROLLER . '/' . _ACTION );
		} elseif (stripos ( $templateFile, '/Addons/' ) === false && stripos ( $templateFile, THINK_PATH ) === false) {
			if (stripos ( $templateFile, '/' ) === false) { // 如index
				$templateFile = T ( $type . '://' . _ADDONS . '@' . _CONTROLLER . '/' . $templateFile );
			} elseif (stripos ( $templateFile, '@' ) === false) { // // 如 UserCenter/index
				$templateFile = T ( $type . '://' . _ADDONS . '@' . $templateFile );
			}
		}
		
		if (stripos ( $templateFile, '/Addons/' ) !== false && ! file_exists ( $templateFile )) {
			$templateFile = ! empty ( $oldFile ) && stripos ( $oldFile, '/' ) === false ? $oldFile : _ACTION;
		}
		// dump ( $templateFile );//exit;
		return $templateFile;
	}
	
	// 通用插件的列表模型
	public function lists($model = null, $page = 0) {
		is_array ( $model ) || $model = $this->getModel ( $model );
		$templateFile = $this->getAddonTemplate ( $model ['template_list'] );
		parent::common_lists ( $model, $page, $templateFile );
	}
	function export($model = null) {
		is_array ( $model ) || $model = $this->getModel ( $model );
		parent::common_export ( $model );
	}
	
	// 通用插件的编辑模型
	public function edit($model = null, $id = 0) {
		
		is_array ( $model ) || $model = $this->getModel ( $model );
		$templateFile = $this->getAddonTemplate ( $model ['template_edit'] );
		parent::common_edit ( $model, $id, $templateFile );
		
		
	}
	
	// 通用插件的增加模型
	public function add($model = null) {
		
		is_array ( $model ) || $model = $this->getModel ( $model );
		$templateFile = $this->getAddonTemplate ( $model ['template_add'] );
		
		parent::common_add ( $model, $templateFile );
	
	}
	
	// 通用插件的删除模型
	public function del($model = null, $ids = null) {
		parent::common_del ( $model, $ids );
	}
	
	// 通用设置插件模型
	public function config() {
		$this->getModel ();
		
		$map ['name'] = _ADDONS;
		$addon = M ( 'addons' )->where ( $map )->find ();
		if (! $addon)
		    $this->error ( '插件未安装' );
		$addon_class = get_addon_class ( $addon ['name'] );
		if (! class_exists ( $addon_class ))
		    trace ( "插件{$addon['name']}无法实例化,", 'ADDONS', 'ERR' );
		$data = new $addon_class ();
		$addon ['addon_path'] = $data->addon_path;
		$addon ['custom_config'] = $data->custom_config;
		$this->meta_title = '设置插件-' . $data->info ['title'];
		$db_config = D ( 'Common/AddonConfig' )->get ( _ADDONS );
		$addon ['config'] = include $data->config_file;
		
		if (IS_POST) {
		    foreach ($addon['config'] as $k=>$vv){
		        if ($vv['type'] == 'material'){
		            $_POST['config'][$k]=$_POST[$k];
		        }
		    }
			$flag = D ( 'Common/AddonConfig' )->set ( _ADDONS, I ( 'config' ) );
			
			if ($flag !== false) {
				$this->success ( '保存成功', Cookie ( '__forward__' ) );
			} else {
				$this->error ( '保存失败' );
			}
		}
		
		
		if ($db_config) {
			foreach ( $addon ['config'] as $key => $value ) {
				if ($value ['type'] != 'group') {
					! isset ( $db_config [$key] ) || $addon ['config'] [$key] ['value'] = $db_config [$key];
				} else {
					foreach ( $value ['options'] as $gourp => $options ) {
						foreach ( $options ['options'] as $gkey => $value ) {
							! isset ( $db_config [$key] ) || $addon ['config'] [$key] ['options'] [$gourp] ['options'] [$gkey] ['value'] = $db_config [$gkey];
						}
					}
				}
			}
		}
		$this->assign ( 'data', $addon );
		// dump($addon);
		if ($addon ['custom_config'])
			$this->assign ( 'custom_config', $this->fetch ( $addon ['addon_path'] . $addon ['custom_config'] ) );
		$this->display ();
	}
	
	// 没有管理页面和配置页面的插件的通用提示页面
	function nulldeal() {
		$this->display ( T ( 'home/Addons/nulldeal' ) );
	}
	function mobileForm() {
		defined ( '_ACTION' ) or define ( '_ACTION', 'mobileForm' );
		
		$model = $this->getModel ( $model );
		
		if (IS_POST) {
			$Model = D ( parse_name ( get_table_name ( $model ['id'] ), 1 ) );
			// 获取模型的字段信息
			$Model = $this->checkAttr ( $Model, $model ['id'] );
			if ($Model->create () && $Model->save ()) {
				$this->success ( '保存' . $model ['title'] . '成功！', U ( 'lists?model=' . $model ['name'] ) );
			} else {
				$this->error ( $Model->getError () );
			}
		} else {
			$fields = get_model_attribute ( $model ['id'] );
			// 获取数据
			$id = I ( 'id' );
			$data = M ( get_table_name ( $model ['id'] ) )->find ( $id );
			
			$this->assign ( 'fields', $fields );
			$this->assign ( 'data', $data );
			
			$this->display ( './Application/Home/View/default/Addons/mobileForm.html' );
		}
	}
	// WAP页面的通用分页HTML
	function _wapPage($count, $row) {
		if ($count <= $row)
			return '';
		
		$page = new \Think\Page ( $count, $row );
		$page->setConfig ( 'theme', '%UP_PAGE% %NOW_PAGE%/%TOTAL_PAGE% %DOWN_PAGE%' );
		$page->setConfig ( 'prev', '上一页<span class="arrow_left"></span>' );
		$page->setConfig ( 'next', '下一页<span class="arrow_right"></span>' );
		return $page->show ();
	}
	function get_package_template() {
		$addons = I ( 'addons' );
		/*
		 * $dir = ONETHINK_ADDON_PATH . $addons . '/View/default/Package';
		 * $url = SITE_URL . '/Addons/' . $addons . '/View/default/Package';
		 *
		 * $dirObj = opendir ( $dir );
		 * while ( $file = readdir ( $dirObj ) ) {
		 * if ($file === '.' || $file == '..' || $file == '.svn' || is_file ( $dir . '/' . $file ))
		 * continue;
		 *
		 * $res ['dirName'] = $res ['title'] = $file;
		 *
		 * // 获取配置文件
		 * if (file_exists ( $dir . '/' . $file . '/info.php' )) {
		 * $info = require_once $dir . '/' . $file . '/info.php';
		 * $res = array_merge ( $res, $info );
		 * }
		 *
		 * // 获取效果图
		 * if (file_exists ( $dir . '/' . $file . '/info.php' )) {
		 * $res ['icon'] = __ROOT__ . '/Addons/'.$addons.'/View/default/Package/' . $file . '/icon.png';
		 * } else {
		 * $res ['icon'] = __IMG__ . '/default.png';
		 * }
		 *
		 * $tempList [] = $res;
		 * unset ( $res );
		 * }
		 * closedir ( $dir );
		 */
		$map ['uid'] = get_mid ();
		$map ['addons'] = $addons;
		
		$Model = D ( 'SucaiTemplate' );
		$tempList = $Model->where ( $map )->select ();
		// dump($tempList);
		if (! $tempList) {
			$default ['addons'] = $addons;
			$default ['template'] = 'default';
			$tempList [] = $default;
		} else {
			$hasDefault = false;
			foreach ( $tempList as $vo ) {
				if ($vo ['template'] == 'default') {
					$hasDefault = true;
					break;
				}
			}
			if ($hasDefault == false) {
				$default ['addons'] = $addons;
				$default ['template'] = 'default';
				$tempList [] = $default;
			}
		}
		// dump($tempList);
		foreach ( $tempList as &$vo ) {
			$info = $this->_readSucaiTemplateInfo ( $vo ['addons'], $vo ['template'] );
			// dump($info);
			$vo ['title'] = $info ['title'];
			$vo ['icon'] = $info ['icon'];
		}
		// dump($tempList);
		$this->ajaxReturn ( $tempList, 'JSON' );
	}
	function getSucaiTemplateInfo() {
		$addons = I ( 'addons' );
		$template = I ( 'template' );
		$res = $this->_readSucaiTemplateInfo ( $addons, $template );
		$this->ajaxReturn ( $res, 'JSON' );
	}
	function _readSucaiTemplateInfo($addons = 'Coupon', $template = 'default') {
		$dir = SITE_PATH . '/SucaiTemplate';
		$infoPath = $dir . '/' . $addons . '/' . $template . '/info.php';
		// dump($infoPath);
		$res ['dirName'] = $template;
		if (file_exists ( $infoPath )) {
			$info = require_once $infoPath;
			$res = array_merge ( $res, $info );
		}
		// 获取效果图
		if (file_exists ( $dir . '/' . $addons . '/' . $template . '.png' )) {
			$res ['icon'] = __ROOT__ . '/SucaiTemplate/' . $addons . '/' . $template . '.png';
		} else {
			$res ['icon'] = __ROOT__ . '/Public/Home/images/no_template_icon.png';
		}
		
		return $res;
	}


	 //根据职位ID查询 职位相关信息
    public function jobInfo($field,$id,$order){
    	if($id){
    		$jobInfo = M('job')->where('id='.$id.' AND status=1')->field($field)->order($order)->find();
    	}else{
    		$jobInfo = M('job')->where('status=1')->field($field)->order($order)->select();
    	}
    	return $jobInfo;
    }

    //根据用户ID查询 用户相关信息
    public function userInfo($field,$token){

    	if($token){
    		$userInfo = M('user')->where('token='.$token)->field($field)->find();
    		
    	}else{
    		$userInfo = M('user')->where('1=1')->field($field)->select();
    	}
    	return $userInfo;
    }

    //获取用户昵称
    public function nickname(){
    	$userInfo = M('user')->where('1=1')->field('uid,nickname')->select();
		$data     = array();
	    foreach($userInfo as $key => $value) {
	    	$data[$value['uid']] = $value['nickname'];
	    }
	    return $data;
    }

    //返回json数据
    /*protected function returnJson($arr){

        $this->ajaxReturn($arr);
    }*/

	function returnJson($message = '成功', $statusCode = 1, $data = array()) {	
		$rs = array (
			'message'    => $message,
			'statusCode' => $statusCode
		);
		$rs = json_encode(array_merge($rs,$data));
		exit ( $rs );
	}
    //接收参数
    protected function getData(){
    	if(IS_POST){
    		$data = I('post.');
    	}else{
    		$data = I('get.');
    	}
    	return $data;
    }
    //验证token
    protected function checkToken($token){
        if(empty($token))$this->returnJson('秘钥不为空',0);
        $setToken = $this->setToken();
        if($setToken != $token)$this->returnJson('秘钥错误',0);
    }
    //设置token
    protected function setToken(){ 
       $username   = 'weicmsclearbxlm';
       $client_key = 'miniprograms';
       $token      = md5($username.date('y').date('m').date('d').$client_key);
       return $token;
    }




    //短信验证码
	 function sms($mobile,$type) {
		
		//$type = I('type');//1 注册，2忘记密码
		if(1 == $type){
			$new = M('user')->where(array('mobile'=>$mobile))->find();
			if(!empty($new)){
				$this->returnJson('手机号码已注册过',0);
			}
		}else{
			$new = M('user')->where(array('mobile'=>$mobile))->find();
			if(empty($new)){
				$this->returnJson('您的手机号还没有注册，请先注册',0);
			}
		}
		if (! self::isMobile ( $mobile )) {
			$this->returnJson( "手机号码不正确!", 0 );
		}
		$code = rand('111111','999999');
		if (! is_numeric ( $code ) || strlen ( $code ) != 6) {
			$this->returnJson( "验证码必须是6位数字!", 0 );
		}
		
		// 以下为核心代码部分
		$ch = curl_init ();
		// 必要参数
		$apikey = C ( "SMS_KEY" ); // 修改为您的apikey(https://www.yunpian.com)登录官网后获取
		$text   = C ( "SMS_TPL" ) . $code;
		
		// 发送短信
		$data = array (
				'text' => $text,
				'apikey' => $apikey,
				'mobile' => $mobile
		);
		curl_setopt ( $ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json' );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // 信任任何证书,https需设置
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ( $data ) );
		
		$json_data = curl_exec ( $ch );
		// 解析返回结果（json格式字符串）
		$array = json_decode ( $json_data, true );
		if ($array ['code'] == 0) {
			session('code',$code);  //设置session
			session('mobile',$mobile);  //设置session
			F('code2',$code);
			F('session_code',session('code'));
			F('session_mobile',session('mobile'));
			$result['code']   =$code;
			$result['mobile'] =$mobile;
			$result['ctime']=time();
			$result['ip']     =$_SERVER["REMOTE_ADDR"];
			M('code')->add($result);
			
			$this->returnJson( "获取验证码成功", 1, $array );
			
		} else {
			$this->returnJson( "获取验证码失败", 0, $array );
		}
	}
	function isMobile($mobile) {
		$search = '/^1[34578]\d{9}$/';
		if (is_numeric ( $mobile ) && preg_match ( $search, $mobile )) {
			return true;
		} else {
			return false;
		}
	}

	//校验验证码
	public function checkCode($mobile){
		$codeInfo = M('code')->where('tel='.$mobile)->order('ctime desc')->find();
		return $codeInfo;
	}
	
}
