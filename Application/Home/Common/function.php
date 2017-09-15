<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 前台公共库文件
 * 主要定义前台公共函数库
 */

/**
 * 获取列表总行数
 *
 * @param string $category
 *            分类ID
 * @param integer $status
 *            数据状态
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_list_count($category, $status = 1)
{
    static $count;
    if (!isset ($count [$category])) {
        $count [$category] = D('Document')->listCount($category, $status);
    }
    return $count [$category];
}

/**
 * 获取段落总数
 *
 * @param string $id
 *            文档ID
 * @return integer 段落总数
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_part_count($id)
{
    static $count;
    if (!isset ($count [$id])) {
        $count [$id] = D('Document')->partCount($id);
    }
    return $count [$id];
}

/**
 * 获取导航URL
 *
 * @param string $url
 *            导航URL
 * @return string 解析或的url
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_nav_url($url)
{
    switch ($url) {
        case 'http://' === substr($url, 0, 7) :
        case 'https://' === substr($url, 0, 8) :
        case '#' === substr($url, 0, 1) :
            break;
        default :
            $url = U($url);
            break;
    }
    return $url;
}

// 运营统计
function tongji($addon)
{
    return false;
    if (empty ($addon) || $addon == 'Tongji')
        return false;

    $data ['token'] = get_token();
    $data ['day'] = date('Ymd');
    $info = M('tongji')->where($data)->find();

    if ($info) {
        $content = unserialize($info ['content']);
        $content [$addon] += 1;

        $save ['content'] = serialize($content);
        M('tongji')->where($data)->save($save);
    } else {
        $content [$addon] = 1;
        $data ['content'] = serialize($content);
        $data ['month'] = date('Ym');
        M('tongji')->add($data);
    }
}

// 获取数据的状态操作
function show_status_op($status)
{
    switch ($status) {
        case 0  :
            return '启用';
            break;
        case 1  :
            return '禁用';
            break;
        case 2  :
            return '审核';
            break;
        default :
            return false;
            break;
    }
}

//获取类别名称 用id获取
function get_about_name($id, $model, $name = 'name')
{
    $result = M($model)->find($id);
    $result = empty($result) ? '' : $result;
    return $result[$name];
}
//获取job
function get_job_name($id)
{
    $where['id'] = $id;
    $result = M('Job')->where($where)->find();
    $result = empty($result) ? '' : $result;
    return $result['title'];
}

//获取用户名 用openid
function use_openid_get_name($openid)
{
    $where['openid'] = $openid;
    $result = M('User')->where($where)->find();
//    echo M()->_sql();

    if(!empty($result)){
        if(!empty($result['truename'])){
            return $result['truename'];
        }elseif(!empty($result['nickname'])){
            return $result['nickname'];
        }elseif(!empty($result['headimgurl'])){
            return $result['headimgurl'];
        }else{
            return $result['uid'].'【无昵称】';
        }
    }else{
        return '用户不存在';
    }

}

//获取工作时间类型
function get_work_time_type($work_time_type)
{
    if (0 == $work_time_type) {
        $work_time_type = '每天';
    } elseif (1 == $work_time_type) {
        $work_time_type = '周末';
    } elseif (2 == $work_time_type) {
        $work_time_type = '工作日';
    } elseif (3 == $work_time_type) {
        $work_time_type = '暑假';
    } elseif (4 == $work_time_type) {
        $work_time_type = '寒假';
    } elseif (5 == $work_time_type) {
        $work_time_type = '其他';
    }
    return $work_time_type;
}

/**
 * 获取月和日
 * $stamp:时间戳
 */
function get_month_day($stamp)
{
    return date('m', strtotime(date('Y-m-d', $stamp))) . "/" . date('d', strtotime(date('Y-m-d', $stamp)));
}
/**
 * 友好时间显示
 * @param $time(时间戳)
 * @return bool|string
 */
function friend_date($time)
{
    if (!$time)
        return false;
    $fdate = '';
    $d = time() - intval($time);
    $ld = $time - mktime(0, 0, 0, 0, 0, date('Y')); //得出年
    $md = $time - mktime(0, 0, 0, date('m'), 0, date('Y')); //得出月
    $byd = $time - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')); //前天
    $yd = $time - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')); //昨天
    $dd = $time - mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天
    $td = $time - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //明天
    $atd = $time - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')); //后天
    if ($d == 0) {
        $fdate = '刚刚';
    } else {
        switch ($d) {
            case $d < $atd:
                $fdate = date('Y年m月d日', $time);
                break;
            case $d < $td:
                $fdate = '后天' . date('H:i', $time);
                break;
            case $d < 0:
                $fdate = '明天' . date('H:i', $time);
                break;
            case $d < 60:
                $fdate = $d . '秒前';
                break;
            case $d < 3600:
                $fdate = floor($d / 60) . '分钟前';
                break;
            case $d < $dd:
                $fdate = floor($d / 3600) . '小时前';
                break;
            case $d < $yd:
                $fdate = '昨天' . date('H:i', $time);
                break;
            case $d < $byd:
                $fdate = '前天' . date('H:i', $time);
                break;
            case $d < $md:
                $fdate = date('m月d日 H:i', $time);
                break;
            case $d < $ld:
                $fdate = date('m月d日', $time);
                break;
            default:
                $fdate = date('Y年m月d日', $time);
                break;
        }
    }
    return $fdate;
}


