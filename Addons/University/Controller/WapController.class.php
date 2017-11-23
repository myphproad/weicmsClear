<?php

namespace Addons\University\Controller;

use Addons\Address\Model\AddressModel;
use Home\Controller\AddonsController;

header("Content-Type:text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');

class WapController extends AddonsController
{
    //商家信息
    public function index()
    {
        $posts = $this->getData();
        $where['city_id'] = $posts['city_id'];
        $university=M('University')->where($where)->order('orderby desc')->field('title,id,city_id')->select();
//        foreach($university as $key=>$value){
//            if ($value['id'] == $userInfo['school']) {
//                $university[$key]['is_choose'] = 1;//已选择
//            } else {
//                $university[$key]['is_choose'] = 0;//否
//            }
//        }
        if($university) {
            $data['info'] = $university;

            $this->returnJson('获取数据成功', 1, $data);
        } else {
            $this->returnJson('获取数据操作失败', 0);
        }
    }

}