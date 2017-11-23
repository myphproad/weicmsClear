<?php

namespace Addons\Address\Model;
use Think\Model;

/**
 * Address模型
 */
class AddressModel extends Model{
    protected $tableName = 'Province';
    /**
     * @Name:获得省级数据
     * @User: 云清(sean)ma.running@foxmail.com
     * @Date: ${DATE}
     * @Time: ${TIME}
     * @param:$pid  $model
     */
    public function get_area_list($model='province',$pid)
    {
        if (empty($pid)) $pid = 0;
        if (empty($model)) {
            $model = 'province';
        } elseif ($model == 'city') {
            if ($pid != 0) {
                $where['pid'] = $pid;
            }
        } else {
            if ($pid != 0) {
                $where['city_id'] = $pid;
            }
        }
        $result = M($model)->where($where)->select();
        $data['data'] = $result;
        return $data;
    }
}
