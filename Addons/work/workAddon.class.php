<?php

namespace Addons\work;
use Common\Controller\Addon;

/**
 * 工作中心插件
 * @author 亿次元科技
 */

    class workAddon extends Addon{

        public $info = array(
            'name'=>'work',
            'title'=>'工作中心',
            'description'=>'这是工作中心管理',
            'status'=>1,
            'author'=>'亿次元科技',
            'version'=>'0.1',
            'has_adminlist'=>1
        );

	public function install() {
		$install_sql = './Addons/work/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/work/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }