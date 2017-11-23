<?php

namespace Addons\Job;
use Common\Controller\Addon;

/**
 * 职位插件
 * @author like
 */

    class JobAddon extends Addon{

        public $info = array(
            'name'=>'Job',
            'title'=>'职位',
            'description'=>'职位相关信息',
            'status'=>1,
            'author'=>'like',
            'version'=>'0.1',
            'has_adminlist'=>1
        );

	public function install() {
		$install_sql = './Addons/Job/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Job/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }