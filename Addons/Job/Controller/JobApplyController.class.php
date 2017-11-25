<?php
namespace Addons\Job\Controller;

use Addons\Sms\Model\SmsModel;
use Common\Model\TemplateMessageModel;
use Home\Controller\AddonsController;

class JobApplyController extends AddonsController
{
    function _initialize()
    {
        $this->model = $this->getModel('JobApply');
        parent::_initialize();
    }

    //商家列表
    public function lists()
    {

        // 关键字搜索
//        $map ['token'] = get_token ();
//        $key = $this->model ['search_key'] ? $this->model ['search_key'] : 'title';
//        if (isset ( $_REQUEST [$key] )) {
//            $map [$key] = array (
//                'like',
//                '%' . htmlspecialchars ( $_REQUEST [$key] ) . '%'
//            );
//            unset ( $_REQUEST [$key] );
//        }
//        // 条件搜索
//        foreach ( $_REQUEST as $name => $val ) {
//            if (in_array ( $name, $fields )) {
//                $map [$name] = $val;
//            }
//        }
//
        $posts = I('');
        //学校查询
        $map=array();
        if(!empty($posts['school'])){
            $university_map['title'] = array('like','%'.trim($posts['school'].'%'));
            $school_id = M('university')->where($university_map)->getField('id');
            if(empty($school_id))$this->error('数据为空!');
            $openid  = M('user')->where('school='.$school_id)->getField('openid',true);
            $map['openid'] = array('in',$openid);
           // session('common_condition', $map);
        }
        //职位名称查询
        if(!empty($posts['job_str'])){
            $job_map['title'] = array('like',trim($posts['job_str']));
            $job_id = M('job')->where($job_map)->getField('id');
            $map['job_id'] = $job_id;
        }

        //用户名搜索
        if(!empty($posts['name'])){
            $user_map['truename'] = array('like','%'.trim($posts['name']).'%');
            $openid = M('user')->where($user_map)->getField('openid');
            $map['openid'] = $openid;
        }
        session('common_condition', $map);
        $list_data = $this->_get_model_list($this->model);
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
            $list_data['list_data'][$key]['mobile'] = get_user_mobile($value['openid']);//openid获取手机号码
            $map['openid'] = array('like',$value['openid']);
            $user_info = M('user')->where($map)->find();
            $university_map['id'] = $user_info['school'];
            $list_data['list_data'][$key]['school'] = M('university')->where($university_map)->getField('title');
        }
        if(1 == I('aa')){
            dump($list_data);die();
        }
        $this->assign($list_data);
        // 通用表单的控制开关
//        $this->assign ( 'add_button', checkRule ( '__MODULE__/__CONTROLLER__/add', $this->mid ) );
//        $this->assign ( 'del_button', checkRule ( '__MODULE__/__CONTROLLER__/del', $this->mid ) );
        $this->assign ( 'search_button', true);
        $this->assign ( 'search_key', 'school');
        $this->assign ( 'placeholder', '请输入学校名称');
        $this->assign ( 'search_button', true);
        $this->assign ( 'search_key1', 'job_str');
        $this->assign ( 'placeholder1', '请输入职位名称');
//        $this->assign ( 'check_all', checkRule ( '__MODULE__/__CONTROLLER__/del', $this->mid ) );
        $this->display();
    }

// 通用插件的增加模型
    public function add()
    {
        $model = $this->model;
        $Model = D(parse_name(get_table_name($model ['id']), 1));
        if (IS_POST) {
            // 获取模型的字段信息
            $Model = $this->checkAttr($Model, $model ['id']);
            if ($Model->create() && $id = $Model->add()) {
                D('Common/Keyword')->set($_POST ['keyword'], _ADDONS, $id, $_POST ['keyword_type'], 'custom_reply_news');
                $this->success('添加' . $model ['title'] . '成功！', U('lists?model=' . $model ['name']));
            } else {
                $this->error($Model->getError());
            }
        } else {
            $fields = get_model_attribute($model ['id']);
            $this->assign('fields', $fields);
            $this->meta_title = '新增' . $model ['title'];
            $this->display();
        }
    }

    public function edit($model = null, $id = 0)
    {
        is_array($model) || $model = $this->model;
        $Model = D(parse_name(get_table_name($model ['id']), 1));
        $id || $id = I('id');
        if (IS_POST) {
            //编辑提交
            if ($_POST['pid'] == $id) {
                $_POST['pid'] = 0;
            }
            // 获取模型的字段信息
            $Model = $this->checkAttr($Model, $model ['id']);
            $res = false;
            $Model->create() && $res = $Model->save();
            if ($res !== false) {
                $this->success('保存' . $model ['title'] . '成功！', U('lists?model=' . $model ['name'], $this->get_param));
            } else {
                $this->error($Model->getError());
            }
        } else {
            //编辑显示
            // 获取一级菜单
            $map ['token'] = get_token();
            $map ['pid'] = 0;
            $map ['id'] = array(
                'not in',
                $id
            );
            $list = $Model->where($map)->select();
            foreach ($list as $v) {
                $extra .= $v ['id'] . ':' . $v ['title'] . "\r\n";
            }
            //获取数据模型属性
            $fields = get_model_attribute($model ['id']);
            if (!empty ($extra)) {
                foreach ($fields as &$vo) {
                    if ($vo ['name'] == 'pid') {
                        $vo ['extra'] .= "\r\n" . $extra;
                    }
                }
            }
            // 获取数据
            $data = M(get_table_name($model ['id']))->find($id);
            $data || $this->error('数据不存在！');
            $token = get_token();
            if (isset ($data ['token']) && $token != $data ['token'] && defined('ADDON_PUBLIC_PATH')) {
                $this->error('非法访问！');
            }
            $this->assign('fields', $fields);
            $this->assign('data', $data);
            $tmpImg = ONETHINK_ADDON_PATH . 'WeiSite/View/default/TemplateSubcate/' . $data['template'] . '/icon.png';
            if (file_exists($tmpImg)) {
                $this->assign('tmp_img', $tmpImg);
            }
            //dump($fields);
            $this->meta_title = '编辑' . $model ['title'];
            $this->display();
        }
    }

    //审核
    public function checkTopics($model = null, $id = 0)
    {
        $id = I('id');
        $ids = I('ids');
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
        $result = $Model->where($where)->setField('status', 1);
        $configMessage = get_addon_config('Job');
        //微信通知 暂时不支持开发 微信小程序没有开放此类接口
//        if ($configMessage['is_mini_close'] == 1) {
//            //查询具体信息
//            $Info = $Model->find($id);
//            $returnData['title'] = get_about_name($Info['job_id'],'Job', 'title');//报名项目ex
//            $returnData['username'] = use_openid_get_name('odosP0aZAWtixHTg0tznBhNnbOKQ');//报名姓名
////            $returnData['username'] = use_openid_get_name($Info['openid']);//报名姓名
//            $returnData['ctime'] = time_format($Info ['ctime']);//报名时间
//            $returnData['remark'] = '记得准时上班哟，预祝上班愉快！';//报名时间
//            $returnData['form_id'] = 'formId！';//报名时间
//            $templateId = $configMessage['applySuccess'];//模板id odosP0YD9juZDpPPJoN69IEPv-pQ
//            $templateMessage=new TemplateMessageModel();//
//            $templateMessage->replyReturnJobApplySuccess($returnData, $templateId, 'odosP0aZAWtixHTg0tznBhNnbOKQ');
//        }
        if ($result !== false) {
            if ($configMessage['is_mobile_close'] == 1) {
                //发送手机短信
                //查询具体信息 job_title
                $Info = $Model->find($id);
                $whereUser['openid'] = $Info['openid'];
                $user_info_mobile = M('User')->where($whereUser)->getField('mobile');
                $job_title = get_about_name($Info['job_id'], 'Job', 'title');//报名项目ex
            }
            if (!empty($user_info_mobile)) {
                $notice_text = $configMessage['applyMobileSuccess'] . $job_title;
                $sms_model = new SmsModel();
                $result = $sms_model->sms_notice_cloud($user_info_mobile, $notice_text);
            }
            $this->success('审核成功');
        } else {
            $this->error('审核失败');
        }
    }

    public function refuse($model = null, $id = 0)
    {
        $id = I('id');
        $ids = I('ids');
        if (empty($id) && empty($ids)) {
            $this->error('请勾选要通过审核的内容');
        }
        if (IS_POST) {
            $token = get_token();
            if (empty($_POST['refuse_season'])) $this->error('拒绝原因也要填写');
            $where = "token = '$token' AND id = $id";
            is_array($model) || $model = $this->model;
            $Model = D(parse_name(get_table_name($model ['id']), 1));
            $data['status'] = 2;
            $data['refuse_season'] = $_POST['refuse_season'];
            $result = $Model->where($where)->save($data);
            $configMessage = get_addon_config('Job');
            //微信通知 暂时不支持开发 微信小程序没有开放此类接口
//        if ($configMessage['is_mini_close'] == 1) {
//            //查询具体信息
//            $Info = $Model->find($id);
//            $returnData['title'] = get_about_name($Info['job_id'],'Job', 'title');//报名项目ex
//            $returnData['username'] = use_openid_get_name('odosP0aZAWtixHTg0tznBhNnbOKQ');//报名姓名
////            $returnData['username'] = use_openid_get_name($Info['openid']);//报名姓名
//            $returnData['ctime'] = time_format($Info ['ctime']);//报名时间
//            $returnData['remark'] = '记得准时上班哟，预祝上班愉快！';//报名时间
//            $returnData['form_id'] = 'formId！';//报名时间
//            $templateId = $configMessage['applySuccess'];//模板id odosP0YD9juZDpPPJoN69IEPv-pQ
//            $templateMessage=new TemplateMessageModel();//
//            $templateMessage->replyReturnJobApplySuccess($returnData, $templateId, 'odosP0aZAWtixHTg0tznBhNnbOKQ');
//        }
            if ($result !== false) {
                if ($configMessage['is_mobile_close'] == 1) {
                    //发送手机短信
                    //查询具体信息 job_title
                    $Info = $Model->find($id);
                    $whereUser['openid'] = $Info['openid'];
                    $user_info_mobile = M('User')->where($whereUser)->getField('mobile');
                }
                if (!empty($user_info_mobile)) {
                    $notice_text = $configMessage['applyMobileError'];
                    $sms_model = new SmsModel();
                    $result = $sms_model->sms_notice_cloud($user_info_mobile, $notice_text);
                }
                $this->success('拒绝操作成功');
            } else {
                $this->error('操作失败');
            }
        } else {
            $this->assign('id', $id);
            $this->display();
        }
    }


    public function get_apply_job_count($id){
        $where['job_id']=$id;
        $result=M('JobApply')->where($where)->count();
        return $result;
    }

}
