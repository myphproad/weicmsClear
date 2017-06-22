<?php

namespace Addons\Appointment;
use Common\Controller\Addon;

/**
 * 我的预约插件
 * @author 无名
 */

    class AppointmentAddon extends Addon{

        public $info = array(
            'name'=>'Appointment',
            'title'=>'我的预约',
            'description'=>'用来查看预约职位以及选择职位类别，工作时间，工作地点。',
            'status'=>1,
            'author'=>'无名',
            'version'=>'0.1',
            'has_adminlist'=>0
        );

	public function install() {
		$install_sql = './Addons/Appointment/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Appointment/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }