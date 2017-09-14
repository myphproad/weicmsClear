<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.thinkphp.cn>
// +----------------------------------------------------------------------

/**
 * 前台配置文件
 * 所有除开系统级别的前台配置
 */
return array (
		// 主题设置
		'DEFAULT_THEME' => 'default', // 默认模板主题名称
		                              
		// 预先加载的标签库
		                              // 'TAGLIB_PRE_LOAD' => 'OT\\TagLib\\Article,OT\\TagLib\\Think',
		'TAGLIB_PRE_LOAD' => 'OT\\TagLib\\Think',
		
		// SESSION 和 COOKIE 配置
		'SESSION_PREFIX' => SITE_DIR_NAME . '_home', // session前缀
		'COOKIE_PREFIX' => SITE_DIR_NAME . '_home',
		
		// 模板相关配置
		'TMPL_PARSE_STRING' => array (
				'__STATIC__' => __ROOT__ . '/Public/static',
				'__ADDONS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/Addons',
				'__IMG__' => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
				'__CSS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
				'__JS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/js' 
		), 


		//'配置项'=>'配置值'
		'SMS_KEY'=>'85ef583eecf045d6272df9e67f73beb6',
		'SMS_TPL'=>'【百效职通车】您的验证码是'	,

		//身高
		'HEIGHT'=>array(
			array('id'=>'1','name'=>'140'),
			array('id'=>'2','name'=>'141'),
			array('id'=>'3','name'=>'142'),
			array('id'=>'4','name'=>'143'),
			array('id'=>'5','name'=>'144'),
			array('id'=>'6','name'=>'145'),
			array('id'=>'7','name'=>'146'),
			array('id'=>'8','name'=>'147'),
			array('id'=>'9','name'=>'148'),
			array('id'=>'10','name'=>'149'),
			array('id'=>'11','name'=>'150'),
			array('id'=>'12','name'=>'151'),
			array('id'=>'13','name'=>'152'),
			array('id'=>'14','name'=>'153'),
			array('id'=>'15','name'=>'154'),
			array('id'=>'16','name'=>'155'),
			array('id'=>'17','name'=>'156'),
			array('id'=>'18','name'=>'157'),
			array('id'=>'19','name'=>'158'),
			array('id'=>'20','name'=>'159'),
			array('id'=>'21','name'=>'160'),
			array('id'=>'22','name'=>'161'),
			array('id'=>'23','name'=>'162'),
			array('id'=>'24','name'=>'163'),
			array('id'=>'25','name'=>'164'),
			array('id'=>'26','name'=>'165'),
			array('id'=>'27','name'=>'166'),
			array('id'=>'28','name'=>'167'),
			array('id'=>'29','name'=>'168'),
			array('id'=>'30','name'=>'169'),
			array('id'=>'31','name'=>'170'),
			array('id'=>'32','name'=>'171'),
			array('id'=>'33','name'=>'172'),
			array('id'=>'34','name'=>'173'),
			array('id'=>'35','name'=>'174'),
			array('id'=>'36','name'=>'175'),
			array('id'=>'37','name'=>'176'),
			array('id'=>'38','name'=>'177'),
			array('id'=>'39','name'=>'178'),
			array('id'=>'40','name'=>'179'),
			array('id'=>'41','name'=>'180'),
			array('id'=>'42','name'=>'181'),
			array('id'=>'43','name'=>'182'),
			array('id'=>'44','name'=>'183'),
			array('id'=>'45','name'=>'184'),
			array('id'=>'46','name'=>'185'),
			array('id'=>'47','name'=>'186'),
			array('id'=>'48','name'=>'187'),
			array('id'=>'49','name'=>'188'),
			array('id'=>'50','name'=>'189'),
			array('id'=>'51','name'=>'190'),
			array('id'=>'52','name'=>'191'),
			array('id'=>'53','name'=>'192'),
			array('id'=>'54','name'=>'193'),
			array('id'=>'55','name'=>'194'),
			array('id'=>'56','name'=>'195'),
			array('id'=>'57','name'=>'196'),
			array('id'=>'58','name'=>'197'),
			array('id'=>'59','name'=>'198'),
			array('id'=>'60','name'=>'199'),
			array('id'=>'61','name'=>'200'),
			),

    
    'WEIGHT'=>array(
    	    array('id'=>'1','name'=>'40'),
			array('id'=>'2','name'=>'41'),
			array('id'=>'3','name'=>'42'),
			array('id'=>'4','name'=>'43'),
			array('id'=>'5','name'=>'44'),
			array('id'=>'6','name'=>'45'),
			array('id'=>'7','name'=>'46'),
			array('id'=>'8','name'=>'47'),
			array('id'=>'9','name'=>'48'),
			array('id'=>'10','name'=>'49'),
			array('id'=>'11','name'=>'50'),
			array('id'=>'12','name'=>'51'),
			array('id'=>'13','name'=>'52'),
			array('id'=>'14','name'=>'53'),
			array('id'=>'15','name'=>'54'),
			array('id'=>'16','name'=>'55'),
			array('id'=>'17','name'=>'56'),
			array('id'=>'18','name'=>'57'),
			array('id'=>'19','name'=>'58'),
			array('id'=>'20','name'=>'59'),
			array('id'=>'21','name'=>'60'),
			array('id'=>'22','name'=>'61'),
			array('id'=>'23','name'=>'62'),
			array('id'=>'24','name'=>'63'),
			array('id'=>'25','name'=>'64'),
			array('id'=>'26','name'=>'65'),
			array('id'=>'27','name'=>'66'),
			array('id'=>'28','name'=>'67'),
			array('id'=>'29','name'=>'68'),
			array('id'=>'30','name'=>'69'),
			array('id'=>'31','name'=>'70'),
			array('id'=>'32','name'=>'71'),
			array('id'=>'33','name'=>'72'),
			array('id'=>'34','name'=>'73'),
			array('id'=>'35','name'=>'74'),
			array('id'=>'36','name'=>'75'),
			array('id'=>'37','name'=>'76'),
			array('id'=>'38','name'=>'77'),
			array('id'=>'39','name'=>'78'),
			array('id'=>'40','name'=>'79'),
			array('id'=>'41','name'=>'80'),
			array('id'=>'42','name'=>'81'),
			array('id'=>'43','name'=>'82'),
			array('id'=>'44','name'=>'83'),
			array('id'=>'45','name'=>'84'),
			array('id'=>'46','name'=>'85'),
			array('id'=>'47','name'=>'86'),
			array('id'=>'48','name'=>'87'),
			array('id'=>'49','name'=>'88'),
			array('id'=>'50','name'=>'89'),
			array('id'=>'51','name'=>'90'),
			array('id'=>'52','name'=>'91'),
			array('id'=>'53','name'=>'92'),
			array('id'=>'54','name'=>'93'),
			array('id'=>'55','name'=>'94'),
			array('id'=>'56','name'=>'95'),
			array('id'=>'57','name'=>'96'),
			array('id'=>'58','name'=>'97'),
			array('id'=>'59','name'=>'98'),
			array('id'=>'60','name'=>'99'),
			array('id'=>'61','name'=>'100'),
         ),
  'WORK_TIME_TYPE'=>array(
  	array('id'=>0,'name'=>'每天'),
  	array('id'=>1,'name'=>'周末'),
  	array('id'=>2,'name'=>'工作日'),
  	array('id'=>3,'name'=>'暑假'),
  	array('id'=>4,'name'=>'寒假'),
  	array('id'=>5,'name'=>'其他'),
  	),
	
); 


