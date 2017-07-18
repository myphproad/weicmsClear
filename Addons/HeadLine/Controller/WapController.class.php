<?php

namespace Addons\Headline\Controller;

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
        $page  = empty(intval($posts['page']))?1:intval($posts['page']);
        $limit = empty(intval($posts['limit']))?10:intval($posts['limit']);

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
        }
        $data['headInfo'] = $headInfo;
        if($data){
            $this->returnJson('操作成功',1,$data);
        }else{
            $this->returnJson('操作失败',0);
        }
	}

    //头条详情
    public function headlineDetails(){
        $posts = $this->getData();
        $id    = intval($posts['id']);
        if(empty($id))$this->returnJson('头条ID不能为空',0);
        $headInfo = M('headline')->where('status=1 AND id='.$id)->field('id,title,tag_id,img_url,comment,ctime')->find();
        if($headInfo){
            $headInfo['comment'] = filter_line_tab($headInfo['comment']);
            $headInfo['ctime']   = date('m.d',$headInfo['ctime']);
            $map['status'] = 1;
            $map['id']     = array('in',$headInfo['tag_id']);
            $tag_arr = M('tag')->where($map)->getField('tname',true);
            $tag_str = implode(',',$tag_arr);
            $headInfo['tag_str'] = $tag_str;
        }


        $data['headInfo'] = $headInfo;

        if($data){
            $this->returnJson('操作成功',1,$data);
        }else{
            $this->returnJson('操作失败',0);
        }

    }
}