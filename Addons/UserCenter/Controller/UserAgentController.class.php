<?php

namespace Addons\UserCenter\Controller;


use Addons\UserCenter\Model\UserAgent;
use Home\Controller\AddonsController;

class UserAgentController extends AddonsController {

    /**
     * @author:like
     * @remark:根据代理人ID 得到下所有用户
     * @data: 2017年9月14日
     */
    public function getUids($recommend_uid){
        $uids = M('user')->where('recommend_uid='.$recommend_uid)->getField('uid',true);
        return $uids;
    }
    /**
     * @author:like
     * @remark:代理用户列表
     * @data: 2017年9月14日10:52:07
     */
    public function lists()
    {
        $uid = is_login();
        $model = $this->getModel('user');
        //判断该用户是否是代理人
        $agent = M('user')->where('uid=' . $uid)->getField('is_agent');
        if ($agent['is_agent'] == 0) {
            $this->error('你无开通代理权限');
        } else {
            $map['recommend_uid'] = $uid;

            $page = I('p', 1, 'intval'); // 默认显示第一页数据
            $isAjax = I('isAjax');
            $isRadio = I('isRadio');

            // 解析列表规则
            $list_data = $this->_get_model_list($model);
            $group_id = I('group_id', 0, 'intval');
            $this->assign('group_id', $group_id);
            if ($group_id) {
                $map2 ['group_id'] = $group_id;
                $uids = M('auth_group_access')->where($map2)->getFields('uid');
                if (empty ($uids)) {
                    $map ['uid'] = 0;
                } else {
                    $map ['uid'] = array('in', $uids);
                }
            }
            //搜索条件
            $nickname = I('nickname');
            if ($nickname) {
                $uidstr = D('Common/User')->searchUser($nickname);
                if ($uidstr) {
                    $map ['uid'] = array('in', $uidstr);
                } else {
                    $map ['uid'] = 0;
                }
            }
            //$row = empty ( $model ['list_row'] ) ? 20 : $model ['list_row'];
            $row = 5;
            $order = 'uid desc';
            // 读取模型数据列表
            $data = M('user')->where($map)
                ->order($order)
                ->page($page, $row)
                ->select();
            /*		$data = M ()->table ( $px . 'public_follow as f' )
                                ->join ( $px . 'user as u ON f.uid=u.uid' )
                                ->field ( 'u.uid,f.openid' )
                                ->where ( $map )
                                ->order ( $order )
                                ->page ( $page, $row )
                                ->select ();*/
//		         dump(M()->_sql());

            foreach ($data as $k => $d) {
                $user = getUserInfo($d ['uid']);
                $user ['openid'] = $d ['openid'];
                $user ['group'] = implode(', ', getSubByKey($user ['groups'], 'title'));
                $data [$k] = $user;
            }
            /* 查询记录总数 */
            $count = M('user')->where($map)->count();
            $list_data ['list_data'] = $data;
            // 分页
            if (intval($count) > $row) {
                $page = new \Think\Page ($count, $row);
                $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
                $list_data ['_page'] = $page->show();
            }else{
                unset($list_data ['_page']);
            }
            // 用户组
            $gmap ['token'] = get_token();
            $gmap ['manager_id'] = $this->mid;
            $auth_group = M('auth_group')->where($gmap)->select();
            $this->assign('auth_group', $auth_group);

            $tagmap ['token'] = get_token();
            $tags = M('user_tag')->where($tagmap)->select();
            $this->assign('tags', $tags);


            $this->assign('syc_wechat', $this->syc_wechat);
            if ($this->syc_wechat) {
                $this->assign('noraml_tips', '请定期手动点击“一键同步微信公众号粉丝”按钮同步微信数据');
            }
//            dump($list_data);
            if ($isAjax) {
                $this->assign('isRadio', $isRadio);
                $this->assign($list_data);
                $this->display('lists_data');
            } else {
                $this->assign($list_data);
                $this->display();
            }
        }
    }
    /**
     * @author:like
     * @remark:代理用户保证金列表
     * @data: 2017年9月14日
     */
    public function userBondLists() {
        $uid = is_login();
        //判断该用户是否是代理人
        $is_agent = M('user')->where('uid='.$uid)->getField('is_agent');
        if($is_agent){
            //得到该代理人下的所有用户
            $uids = $this->getUids($uid);
            $bond_map['user_id'] = array('in',$uids);
            //用户 保证金金额 状态 创建时间
            $info = M('user_bond_logs')->where($bond_map)->select();
            if($info){
                foreach($info as $key=>$value){
                    $info[$key]['user_name'] = M('user')->where('uid='.$value['user_id'])->getField('nickname');
                    if(0 == $value['status']){
                        $info[$key]['str_status'] = '未缴纳';
                    }elseif(1 == $value['status']){
                        $info[$key]['str_status'] = '已缴纳';
                    }elseif(3 == $value['status']){
                        $info[$key]['str_status'] = '保证金已退还';
                    }
                    $info[$key]['ctime'] = date('Y-m-d H:i:s',$value['ctime']);
                }
            }else{
                $this->error('无数据');
            }
        }else{
            $this->error('非代理人');
        }
        $this->assign($info);
        $this->display();
    }

    /**
     * @author:like
     * @remark:代理用户预约列表
     * @data: 2017年9月14日
     */
    public function jobSubscribeLists(){
        $uid = is_login();
        //判断该用户是否是代理人
        $is_agent = M('user')->where('uid='.$uid)->getField('is_agent');
        if($is_agent){
            //得到该代理人下的所有用户
            $uids = $this->getUids($uid);
            $job_map['user_id'] = array('in',$uids);
            //用户 职位类型(名称) 时间类型 工作地点 创建时间
            $info = M('job_subscribe')->where($job_map)->select();
            if($info){
                foreach($info as $key=>$value){
                    $info['list_data'][$key]['user_id']  = get_nickname($value['user_id']);
                    $info['list_data'][$key]['job_type'] = get_about_name($value['user_id'],'job_name');
                    $info['list_data'][$key]['area_id']  = get_about_name($value['area_id'],'area');
                    if(0 == $value['work_time_type']){
                        $info['list_data'][$key]['work_time_type'] = '每天';
                    }elseif(1 == $value['work_time_type']){
                        $info['list_data'][$key]['work_time_type'] = '周末';
                    }elseif(2 == $value['work_time_type']){
                        $info['list_data'][$key]['work_time_type'] = '工作日';
                    }elseif(3 == $value['work_time_type']){
                        $info['list_data'][$key]['work_time_type'] = '暑假';
                    }elseif(4 == $value['work_time_type']){
                        $info['list_data'][$key]['work_time_type'] = '寒假';
                    }else{
                        $info['list_data'][$key]['work_time_type'] = '其他';
                    }
                    $info['list_data'][$key]['ctime'] = date('Y-m-d',$value['ctime']);
                }
            }
        }
        $this->assign($info);
        $this->display();
    }

    /**
     * @author:like
     * @remark:代理用户申请列表
     * @data: 2017年9月14日
     */
    public function jobApplyLists(){
        $uid = is_login();
        //判断该用户是否是代理人
        $is_agent = M('user')->where('uid='.$uid)->getField('is_agent');
        if($is_agent) {
            //得到该代理人下的所有用户
            $uids = $this->getUids($uid);
            $job_map['user_id'] = array('in', $uids);
            //用户名称 职位名称 申请状态 创建时间
            $list_data = M('job_apply')->where($job_map)->select();
            $jobTitle = $this->jobInfo('id,title', '', 'id desc');
            $data = array();
            foreach ($jobTitle as $key => $value) {
                $data[$value['id']] = $value['title'];
            }
            foreach ($list_data['list_data'] as $key => $value) {
                if (empty($value['user_id'])) {
                    $list_data['list_data'][$key]['user_id'] = use_openid_get_name($value['openid']);//openid获取用户
                } else {
                    $list_data['list_data'][$key]['user_id'] = get_nickname($value['user_id']);
                }
                $list_data['list_data'][$key]['job_id'] = get_about_name($data[$value['job_id']], 'job', 'title');
            }
            dump($list_data);die();
            $this->assign($list_data);
            $this->display();
        }
    }

    /**
     * @author:like
     * @remark:添加代理
     * @data: 2017年9月14日13:30:55
     */
    public function addAgent(){
        $posts = I();
        if(empty($posts['uid'])) $this->error ( '用户ID不能为空!' );
        $uid = intval($posts['uid']);
        //判断该用户是否是代理人
        $is_agent = M('user')->where('uid='.$uid)->getField('is_agent');
        if(1 == $is_agent){
            $this->error('该用户已经是代理人');
        }else{
            $data['recommend_uid'] = 0;
            $data['is_agent']      = 1;
            $res = M('user')->where('uid='.$uid)->save($data);
            if($res){
                $this->success('操作成功',U('addon/UserCenter/UserCenter/lists'));
            }else{
                $this->error('操作失败');
            }
        }
    }
}
