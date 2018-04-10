<?php

namespace Addons\UserCenter\Controller;

use Addons\UserCenter\Model\UserCashModel;
use Home\Controller\AddonsController;

class UserBondLogsController extends AddonsController
{
    var $syc_wechat = true;

    // 是否需要与微信端同步，目前只有认证的订阅号和认证的服务号可以同步
    function _initialize()
    {
        $this->model = $this->getModel('UserBondLogs');
        parent::_initialize();
        $this->syc_wechat = C('USER_LIST');
    }

    /**
     * 显示微信用户列表数据
     */
    public function lists()
    {
        $posts = I();
        $map = array();
        //用户名搜索
        if (!empty($posts['nickname'])) {
            $user_map['nickname'] = array('like', '%' . trim($posts['nickname']) . '%');
            $user_id = M('user')->where($user_map)->getField('uid');
            if (empty($user_id)) $this->error('数据为空!');
            $this->assign('nickname', $posts['nickname']);
            $map['user_id'] = $user_id;
        }
        //时间搜索
        if (!empty($posts['start_time']) && !empty($posts['end_time'])) {
            $start_time = strtotime($posts['start_time']);
            $end_time = strtotime($posts['end_time']);
            $map['ctime'][] = array('EGT', $start_time);
            $map['ctime'][] = array('ELT', $end_time);
            $this->assign('start_time', $posts['start_time']);
            $this->assign('end_time', $posts['end_time']);
        } elseif (!empty($posts['start_time']) && empty($posts['end_time'])) {
            $start_time = strtotime($posts['start_time']);
            $end_time = time();
            $map['ctime'][] = array('EGT', $start_time);
            $map['ctime'][] = array('ELT', $end_time);
            $this->assign('start_time', $posts['start_time']);
        }
        //下拉选择-数据分配 保证金状态 1已经支付完成 3退保证金审核通过
        $map['type'] = 0;//缴纳
        $status_value = 1;
        if (is_numeric(I('status'))) {
            $status_value = I('status');
        }
        $this->assign('status_value', $status_value);
        $map['status'] = $status_value;

        session('common_condition', $map);
        $list_data = $this->_get_model_list($this->model);
//        echo M()->getLastSql();
        foreach ($list_data['list_data'] as $key => $value) {
            //获取用户和手机号码
            if($value['user_id']){
                $user_map['uid']=$value['user_id'];
                $user_id = M('user')->where($user_map)->find();
                $list_data['list_data'][$key]['mobile'] = $user_id['mobile'];
                $list_data['list_data'][$key]['truename'] = $user_id['truename'];
                $list_data['list_data'][$key]['user_id'] = $user_id['nickname'];
            }
            if ($value['status'] == 1) {
                $list_data['list_data'][$key]['status'] = '已缴纳';
            } else {
                $list_data['list_data'][$key]['status'] = '未缴纳';
            }
        }
        $this->assign($list_data);
        $this->assign('search_button', true);
        $this->assign('del_button', true);
        $this->assign('add_button', false);

        $this->assign('search_key', 'nickname');
        $this->assign('placeholder', '请输入用户名');
        $this->display();
    }

    /**
     * 显示微信用户列表数据
     */
    public function bond_cash_logs()
    {
        $posts = I();
        $map = array();
        //用户名搜索
        if (!empty($posts['nickname'])) {
            $user_map['nickname'] = array('like', '%' . trim($posts['nickname']) . '%');
            $user_id = M('user')->where($user_map)->getField('uid');
            if (empty($user_id)) $this->error('数据为空!');
            $this->assign('nickname', $posts['nickname']);
            $map['user_id'] = $user_id;
        }
        //时间搜索
        if (!empty($posts['start_time']) && !empty($posts['end_time'])) {
            $start_time = strtotime($posts['start_time']);
            $end_time = strtotime($posts['end_time']);
            $map['ctime'][] = array('EGT', $start_time);
            $map['ctime'][] = array('ELT', $end_time);
            $this->assign('start_time', $posts['start_time']);
            $this->assign('end_time', $posts['end_time']);
        } elseif (!empty($posts['start_time']) && empty($posts['end_time'])) {
            $start_time = strtotime($posts['start_time']);
            $end_time = time();
            $map['ctime'][] = array('EGT', $start_time);
            $map['ctime'][] = array('ELT', $end_time);
            $this->assign('start_time', $posts['start_time']);
        }
        //下拉选择-数据分配 保证金状态 1已经支付完成 3退保证金审核通过
        $map['type'] = 1;//退保
        $status_value = 0;
        if (is_numeric(I('status'))) {
            $status_value = I('status');
        }
        $this->assign('status_value', $status_value);
        $map['status'] = $status_value;

        $this->assign('status_value', I('status'));

        session('common_condition', $map);
        $list_data = $this->_get_model_list($this->model);
//        echo M()->getLastSql();

        foreach ($list_data['list_data'] as $key => $value) {
            if ($value['status'] == 3) {
                $list_data['list_data'][$key]['status_value'] = '退保成功';
            } elseif ($value['status'] == 4) {
                $list_data['list_data'][$key]['status_value'] = '拒绝退保';
            } else {
                $list_data['list_data'][$key]['status_value'] = '申请退保';
            }
            //获取用户和手机号码
            if($value['user_id']){
                $user_map['uid']=$value['user_id'];

                $user_id = M('user')->where($user_map)->find();
                $list_data['list_data'][$key]['mobile'] = $user_id['mobile'];
                $list_data['list_data'][$key]['truename'] = $user_id['truename'];
                $list_data['list_data'][$key]['user_id'] = $user_id['nickname'];
            }
        }

        $this->assign($list_data);
        $this->assign('search_button', true);
        $this->assign('search_key', 'nickname');
        $this->assign('placeholder', '请输入用户名');
        $this->display();
    }

    //审核
    public function audit($model = null, $id = 0)
    {
        $id = I('id');
        $ids = I('ids');
        $status = 3;

        if (empty($id) && empty($ids)) {
            $this->error('请勾选要通过审核的内容');
        }
        $token = get_token();
        if (is_array($ids)) {
            $id = $ids;
            $id = implode(',', $id);
            $where = "token = '$token' AND id in($id)";
        } else {
            $where = "token = '$token' AND id = $id";
        }
        is_array($model) || $model = $this->model;
        $Model = D(parse_name(get_table_name($model ['id']), 1));

        $bond = $Model->where($where)->select();
        if ($bond) {
            $usersID = array();
            foreach ($bond as $key => $value) {
                if ($value['status'] == 4 || $value['status'] == 3) {
                    $this->error('选中用户已经拒绝或者审核通过了,不能再审核');
                }
                $usersID[] = $value['user_id'];
            }
            $whereUser['uid'] = array('in', $usersID);
            $data['bond'] = 0.00;
            $resultUser = M('User')->where($whereUser)->save($data);
            $result = $Model->where($where)->setField('status', $status);
            if ($resultUser !== false && $result !== false) {
                $this->success('审核成功');
            } else {
                $this->error('审核失败');
            }
        }
    }

    //拒绝
    public function refuse($model = null, $id = 0)
    {
        $ids = I('ids');
        $status = 4;

        if (empty($ids)) {
            $this->error('请勾选要通过审核的内容');
        }

        $token = get_token();
        $id = $ids;
        $id = implode(',', $id);
        $where = "token = '$token' AND id in($id)";
        is_array($model) || $model = $this->model;
        $Model = D(parse_name(get_table_name($model ['id']), 1));

        $result_bond = $Model->where($where)->select();
        foreach ($result_bond as $key=>$value){
            if ($value['status'] == 4 || $value['status'] == 3) {
                $this->error('选中用户已经拒绝或者审核通过了,不能再审核');
            }
        }

        $result = $Model->where($where)->setField('status', $status);
        if ($result !== false) {
            $this->success('审核成功');
        } else {
            $this->error('审核失败');
        }
    }
    public function sendCash($model = null, $id = 0)
    {
        $ids = I('id');
        if (empty($ids)) {
            $this->error('请勾选要通过审核的内容');
        }
        $token = get_token();
        $where['token'] = $token;

        $where['id'] = $ids;
        $logs_info = M('UserBondLogs')->where($where)->find();

        if ($logs_info['status'] == 4 || $logs_info['status'] == 3) {
            $this->error('选中用户已经拒绝或者审核通过了,不能再审核');
        }
        $openIds = $logs_info['openid'];
        if(!$openIds){
            $this->error('该用户信息不完整，无法通过线上退保');
        }
        is_array($model) || $model = $this->model;
        $Model = D(parse_name(get_table_name($model ['id']), 1));
        $cashHandleModel = new UserCashModel();
        $msgData = $cashHandleModel->getBonds ($openIds,$ids);
        if($msgData['msg_code']==1){
//                $this->_sendWeixinMail ($content_id,$this->mid,3);
            //工资归零
            $whereUser['uid'] =$logs_info['user_id'];
            $data['bond'] = 0.00;
            $status = 3;
            $resultUser = M('User')->where($whereUser)->save($data);
            $result = $Model->where($where)->setField('status', $status);

            if ($result && $resultUser) {
                $this->success('退保成功');
            } else {
                $this->error('保存状态失败，但是退保成功');
            }
        }else{
            $this->error($msgData['msg']);
        }
    }
}