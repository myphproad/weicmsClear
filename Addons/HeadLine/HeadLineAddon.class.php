<?php

namespace Addons\HeadLine;
use Common\Controller\Addon;

/**
 * 头条插件
 * @author like
 */

    class HeadineAddon extends Addon{

        public $info = array(
            'name'=>'Headline',
            'title'=>'头条',
            'description'=>'头条相关信息',
            'status'=>1,
            'author'=>'like',
            'version'=>'0.1',
            'has_adminlist'=>1
        );

	public function install() {
		$install_sql = './Addons/HeadLine/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/HeadLine/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }