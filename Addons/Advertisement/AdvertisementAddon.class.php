<?php

namespace Addons\Advertisement;
use Common\Controller\Addon;

/**
 * 广告管理插件
 * @author sean
 */

    class AdvertisementAddon extends Addon{

        public $info = array(
            'name'=>'Advertisement',
            'title'=>'广告管理',
            'description'=>'广告管理中心，可以指定位置设置具体广告；也可以控制前端首页幻灯片',
            'status'=>1,
            'author'=>'sean',
            'version'=>'0.1',
            'has_adminlist'=>1
        );

	public function install() {
		$install_sql = './Addons/Advertisement/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Advertisement/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }