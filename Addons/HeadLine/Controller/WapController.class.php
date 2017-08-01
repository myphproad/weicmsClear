<?php

namespace Addons\HeadLine\Controller;

use Home\Controller\AddonsController;
header("Content-Type:text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');

class WapController extends AddonsController {

    function _initialize() {

    }

	//头条列表
	public function headlineList(){
        //头条简介 图片 标签
        $posts = $this->getData();
        $page  = intval($posts['page'])?intval($posts['page']):1;
        $limit = intval($posts['limit'])?intval($posts['limit']):5;

        $headInfo = M('headline')->where('status=1')
                    ->field('id,title,tag_id,img_url')
                    ->order('id desc,ctime desc')
                    ->limit($limit)
                    ->page($page)
                    ->select();
        foreach($headInfo as $key=>$value){
            $tag_arr = array();
            $map['status'] = 1;
            $map['id'] = array('in',$value['tag_id']);
            $tag_arr = M('tag')->where($map)->getField('tname',true);
            $tag_str = implode(',',$tag_arr);
            $headInfo[$key]['tag_str'] = $tag_str;
            $headInfo[$key]['img_url'] = get_cover_url($value['img_url']);

        }
        $data['headInfo'] = $headInfo;
        if($data){
            $this->returnJson('操作成功',1,$data);
        }else{
            $this->returnJson('操作成功',0);
        }
	}

    //头条详情
    public function headLineDetails(){
        $posts = $this->getData();
        $id    = intval($posts['id']);
        $headInfo = M('headline')->where('status=1 AND id='.$id)->field('id,title,tag_id,img_url,comment,ctime')->find();
        $headInfo['comment'] = filter_line_tab($headInfo['comment']);

        $map['status'] = 1;
        $map['id']     = array('in',$headInfo['tag_id']);
        $tag_arr = M('tag')->where($map)->getField('tname',true);
        $tag_str = implode(',',$tag_arr);
        $headInfo['tag_str'] = $tag_str;
        $headInfo['ctime']=time_format($headInfo['ctime']);//2017-07-05 17:33格式
        $headInfo['img_url'] = get_cover_url($headInfo['img_url']);
        $data['headInfo'] = $headInfo;

        if($data){
            $this->returnJson('操作成功',1,$data);
        }else{
            $this->returnJson('操作成功',0);
        }

    }
}