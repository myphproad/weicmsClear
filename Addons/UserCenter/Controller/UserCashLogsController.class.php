<?php

namespace Addons\UserCenter\Controller;

use Addons\UserCenter\Model\UserCashModel;
use Home\Controller\AddonsController;

class UserCashLogsController extends AddonsController
{
    var $syc_wechat = true;

    // 是否需要与微信端同步，目前只有认证的订阅号和认证的服务号可以同步
    function _initialize()
    {
        $this->model = $this->getModel('UserCashLogs');
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
        if (!empty($posts['name'])) {
            $user_map['truename'] = array('like', '%' . trim($posts['name']) . '%');
            $openid = M('user')->where($user_map)->getField('openid');
            if (empty($openid)) $this->error('数据为空!');
            $map['openid'] = $openid;
        }
        session('common_condition', $map);
        $list_data = $this->_get_model_list($this->model);
        /*****所属职位*******/
        $jobTitle = $this->jobInfo('id,title', '', 'id desc');
        $data = array();
        foreach ($jobTitle as $key => $value) {
            $data[$value['id']] = $value['title'];
        }
        /*****所属职位*******/

        foreach ($list_data['list_data'] as $key => $value) {
            if ($value['status'] == 0) {
                $list_data['list_data'][$key]['status_value'] = "申请提现";
            }else if ($value['status'] == 1){
                $list_data['list_data'][$key]['status_value'] = "成功提现";
            }else  if ($value['status'] == -1){
                $list_data['list_data'][$key]['status_value'] = "拒绝提现";

            }
            $list_data['list_data'][$key]['job_id'] = $data[$value['job_id']];
            $list_data['list_data'][$key]['user_id'] = get_nickname($value['user_id']);
        }
        $this->assign($list_data);
        $this->assign('search_button', true);
        $this->assign('add_button', false);
        $this->assign('search_key', 'name');
        $this->assign('placeholder', '请输入用户名');
        $this->display();
    }

    //拒绝
    public function changeStatus($model = null, $id = 0)
    {
        $ids = I('ids');
        if (empty($ids)) {
            $this->error('请勾选要拒绝的用户');
        }
        $token = get_token();
        $where['token'] = $token;
        $id = $ids;
        $id = implode(',', $id);
        $where['id'] = array('in', $id);
        $cash_logs_info = M('user_cash_logs')->where($where)->select();

        $openIds = array();
        foreach ($cash_logs_info as $key => $value) {
            $openIds[] = $value['openid'];
        }
        $openIds = array_unique($openIds);
        is_array($model) || $model = $this->model;
        $Model = D(parse_name(get_table_name($model ['id']), 1));
            //拒绝
        $status = -1;
        //还原工资金额
        foreach ($cash_logs_info as $key => $value) {
            $user_map['openid'] = array('in', $openIds);
            M('user')->where($user_map)->setInc('salary', $value['money']);
        }
        $result = $Model->where($where)->setField('status', $status);
        if ($result !== false) {
            $this->success('拒绝成功');
        } else {
            $this->error('拒绝失败');
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
        $cash_logs_info = M('user_cash_logs')->where($where)->find();

        if($cash_logs_info['status']!=0){
            $this->error('选中了拒绝或者已经提现成功的用户！');
        }
        $openIds = $cash_logs_info['openid'];
        if(!$openIds){
            $this->error('该用户信息不完整，无法通过线上发放红包');
        }
        is_array($model) || $model = $this->model;
        $Model = D(parse_name(get_table_name($model ['id']), 1));
        $status = 1;
        $cashHandleModel = new UserCashModel();
        $msgData = $cashHandleModel->getCash ($openIds,$ids);
        if($msgData['msg_code']==1){
//                $this->_sendWeixinMail ($content_id,$this->mid,3);
            //工资归零
            $salary_data['salary'] = 0;
            $salary_where['openid'] = $openIds;
            M('user')->where($salary_where)->save($salary_data);
            $result = $Model->where($where)->setField('status', $status);
            if ($result) {
                $this->success('发放成功');
            } else {
                $this->error('保存状态失败，但是发放成功');
            }
        }else{
            $this->error($msgData['msg']);
        }
    }

}