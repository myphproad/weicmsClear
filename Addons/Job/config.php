<?php
return array(
	'is_mini_close' => array ( // 配置在表单中的键名 ,这个会是config[random]
		'title' => '开启小程序审核消息通知:', // 表单的文字
		'type' => 'radio', // 表单的类型：text、textarea、checkbox、radio、select等
		'options' => array ( // select 和radion、checkbox的子选项
			'1' => '是', // 值=>文字
			'0' => '否'
		),
		'value' => '0'
	), // 表单的默认值
	'is_mobile_close' => array ( // 配置在表单中的键名 ,这个会是config[random]
		'title' => '开启手机短信审核消息通知:', // 表单的文字
		'type' => 'radio', // 表单的类型：text、textarea、checkbox、radio、select等
		'options' => array ( // select 和radion、checkbox的子选项
			'1' => '是', // 值=>文字
			'0' => '否'
		),
		'value' => '0'
	), // 表单的默认值
	'applyMiniSuccess' => array (
		'title' => '用户职位审核成功提示小程序模板消息id:',
		'type' => 'text',
		'value' => 'TwcqJhp5d8sEhFsWPaeM5Qg2UXY9eEszm_EXRSnUFLI',
		'tip' => ''
	),
	'applyMiniError' => array (
		'title' => '用户职位审核拒绝提示小程序模板消息id:',
		'type' => 'text',
		'value' => '5QKa93TBooOgeYPViuYQKs6c-ySypDVwrBCEII8x78M',
		'tip' => ''
	),
	'applyMobileSuccess' => array (
		'title' => '用户职位审核成功提示手机模板消息内容:',
		'type' => 'text',
		'value' => '您已经成功申请职位：',
		'tip' => ''
	),
	'applyMobileError' => array (
		'title' => '用户职位审核拒绝提示模板消息内容:',
		'type' => 'text',
		'value' => '您申请的职位审核未通过，详情查看百效直通车小程序平台',
		'tip' => ''
	)

);
					