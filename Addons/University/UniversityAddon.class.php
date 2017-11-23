<?php

namespace Addons\University;
use Common\Controller\Addon;

/**
 * 学校管理插件
 * @author sean
 */

    class UniversityAddon extends Addon{

        public $info = array(
            'name'=>'University',
            'title'=>'学校管理',
            'description'=>'学校管理',
            'status'=>1,
            'author'=>'sean',
            'version'=>'0.1',
            'has_adminlist'=>1
        );

	public function install() {
		$install_sql = './Addons/University/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/University/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }