<?php

namespace Addons\Address;
use Common\Controller\Addon;

/**
 * 地区管理插件
 * @author like
 */

    class AddressAddon extends Addon{

        public $info = array(
            'name'=>'Address',
            'title'=>'地区管理',
            'description'=>'地区管理',
            'status'=>1,
            'author'=>'like',
            'version'=>'0.1',
            'has_adminlist'=>1
        );

	public function install() {
		$install_sql = './Addons/Address/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Address/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }