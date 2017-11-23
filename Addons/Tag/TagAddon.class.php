<?php

namespace Addons\Tag;
use Common\Controller\Addon;

/**
 * 标签管理插件
 * @author like
 */

    class TagAddon extends Addon{

        public $info = array(
            'name'=>'Tag',
            'title'=>'标签管理',
            'description'=>'标签管理中心',
            'status'=>1,
            'author'=>'like',
            'version'=>'0.1',
            'has_adminlist'=>1
        );

	public function install() {
		$install_sql = './Addons/Tag/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Tag/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }