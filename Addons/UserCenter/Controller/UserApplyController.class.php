<?php
namespace Addons\UserCenter\Controller;
use Home\Controller\AddonsController;
class UserApplyController extends AddonsController {
	var $syc_wechat = true;
	// 是否需要与微信端同步，目前只有认证的订阅号和认证的服务号可以同步
	function _initialize() {
		parent::_initialize ();
		$this->syc_wechat = C ( 'USER_LIST' );
	}
	
	/**
	 * 显示微信用户列表数据
	 */
	public function lists() {
		/*// 搜索条件
		$type = I ( 'type', 0, 'intval' );
		$param['mdm']=$_GET['mdm'];
		$param ['type'] = 0;
		$res ['title'] = '订单管理';
		$res ['url'] = addons_url ( 'HumanTranslation://Order/lists', $param );
		$res ['class'] = $type == $param ['type'] && _ACTION == 'lists' ? 'current' : '';
		$nav [] = $res;*/
		$isAjax = I ( 'isAjax' );
		$isRadio = I ( 'isRadio' );
		$model = $this->getModel ( 'UserApply' );
		$page = I ( 'p', 1, 'intval' ); // 默认显示第一页数据
		// 解析列表规则
		$list_data = $this->_list_grid ( $model );
		$row = empty ( $model ['list_row'] ) ? 20 : $model ['list_row'];
		// 读取模型数据列表
		$name = parse_name ( get_table_name ( $model ['id'] ), true );
		$data = M ( $name )->field ( true )->order ( 'id desc' )->page ( $page, $row )->select ();
		$dao = D ( 'UserApply' );
		/* 查询记录总数 */
		$count = M ( $name  )->count ();
		$list_data ['list_data'] = $data;
		$this->assign('search_url',U('lists',array('mdm'=>$_GET['mdm'])));
		// 分页
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$list_data ['_page'] = $page->show ();
		}
		if ($isAjax) {
			$this->assign('isRadio',$isRadio);
			$this->assign ( $list_data );
			$this->display ( 'ajax_lists_data' );
		} else {
			$this->assign ( $list_data );
			// dump($list_data);
			$this->display ();
		}
	}
	// 通用插件的编辑模型
	public function edit() {
		$model = $this->model;
		$id = I ( 'id' );
		if (IS_POST) {
			$Model = D ( parse_name ( get_table_name ( $model ['id'] ), 1 ) );
			// 获取模型的字段信息
			$Model = $this->checkAttr ( $Model, $model ['id'] );
			if ($Model->create () && $Model->save ()) {
				D ( 'Common/Keyword' )->set ( $_POST ['keyword'], _ADDONS, $id, $_POST ['keyword_type'], 'custom_reply_news' );
				$this->success ( '保存' . $model ['title'] . '成功！', U ( 'lists?model=' . $model ['name'] ) );
			} else {
				$this->error ( $Model->getError () );
			}
		} else {
			$fields = get_model_attribute ( $model ['id'] );
			$extra = $this->getCateData ();
			if (! empty ( $extra )) {
				foreach ( $fields as &$vo ) {
					if ($vo ['name'] == 'cate_id') {
						$vo ['extra'] .= "\r\n" . $extra;
					}
				}
			}
			// 获取数据
			$data = M ( get_table_name ( $model ['id'] ) )->find ( $id );
			$data || $this->error ( '数据不存在！' );
			$token = get_token ();
			if (isset ( $data ['token'] ) && $token != $data ['token'] && defined ( 'ADDON_PUBLIC_PATH' )) {
				$this->error ( '非法访问！' );
			}
			$this->assign ( 'fields', $fields );
			$this->assign ( 'data', $data );
			$this->meta_title = '编辑' . $model ['title'];
			$this->display ();
		}
	}
	// 通用插件的增加模型
	public function add() {
		$model = $this->model;
		$Model = D ( parse_name ( get_table_name ( $model ['id'] ), 1 ) );
		if (IS_POST) {
			// 获取模型的字段信息
			$Model = $this->checkAttr ( $Model, $model ['id'] );
			if ($Model->create () && $id = $Model->add ()) {
				D ( 'Common/Keyword' )->set ( $_POST ['keyword'], _ADDONS, $id, $_POST ['keyword_type'], 'custom_reply_news' );
				$this->success ( '添加' . $model ['title'] . '成功！', U ( 'lists?model=' . $model ['name'] ) );
			} else {
				$this->error ( $Model->getError () );
			}
		} else {
			$fields = get_model_attribute ( $model ['id'] );
			$extra = $this->getCateData ();
			if (! empty ( $extra )) {
				foreach ( $fields as &$vo ) {
					if ($vo ['name'] == 'cate_id') {
						$vo ['extra'] .= "\r\n" . $extra;
					}
				}
			}
			$this->assign ( 'fields', $fields );
			$this->meta_title = '新增' . $model ['title'];
			$this->display ();
		}
	}
	// 通用插件的删除模型
	public function del() {
		parent::common_del ( $this->model );
	}
	// 获取所属分类
	function getCateData() {
		$map ['is_show'] = 1;
		$map ['token'] = get_token ();
		$list = M ( 'shop_goods_category' )->where ( $map )->select ();
		foreach ( $list as $v ) {
			$extra .= $v ['id'] . ':' . $v ['title'] . "\r\n";
		}
		return $extra;
	}
	function set_show() {
		$save ['is_show'] = 1 - I ( 'is_show' );
		$map ['id'] = I ( 'id' );
		$res = M ( 'shop_goods' )->where ( $map )->save ( $save );
		$this->success ( '操作成功' );
	}
	function detail() {
		$id = I ( 'id' );
		$orderDao = D ( 'Addons://Shop/Order' );
		$orderInfo = $orderDao->getInfo ( $id );
		$address_id = $orderInfo ['address_id'];
		$addressInfo = D ( 'Addons://Shop/Address' )->getInfo ( $address_id );
		$orderInfo ['goods'] = json_decode ( $orderInfo ['goods_datas'], true );
		//dump ( $orderInfo );
		$this->assign ( 'info', $orderInfo );
		$this->assign ( 'addressInfo', $addressInfo );
		$this->display ();
	}
}
