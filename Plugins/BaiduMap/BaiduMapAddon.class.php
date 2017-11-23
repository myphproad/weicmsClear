<?php

namespace Plugins\BaiduMap;
use Common\Controller\Plugin;

/**
 * 百度定位插件
 * @author 云清
 */

    class BaiduMapAddon extends Plugin{

        public $info = array(
            'name'=>'BaiduMap',
            'title'=>'百度地图坐标定位',
            'description'=>'这是一个百度地图定位',
            'status'=>1,
            'author'=>'云清',
            'version'=>'0.1',
            'has_adminlist'=>0
        );

	public function install() {
		$install_sql = './Plugins/BaiduMap/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Plugins/BaiduMap/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

		//实现的UploadFiles钩子方法
		public function BaiduMap($param){
//            if (empty($param['value'])) {
//                $param['value'] = json_encode(array());
//            }
			$this->assign('param', $param);
			$this->display('index');
		}

    }