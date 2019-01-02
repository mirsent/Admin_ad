<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    /**
     * 标签列表
     */
    public function get_tag_list()
    {
        $cond['status'] = C('STATUS_Y');
        $data = M('tag')->where($cond)->field('id,tag_name')->select();

        ajax_return(1, '标签列表', $data);
    }

    /**
     * 根据标签获取广告列表
     * @param tag_id 标签ID
     */
    public function get_advertise_list()
    {
        $cond = [
            'a.status' => C('STATUS_Y'),
            'tag_id'   => I('tag_id')
        ];
        $data = D('Advertise')->getAdvertiseDataApi($cond);

        ajax_return(1, '广告列表', $data);
    }

    /**
     * 广告详情
     * @param advertise_id 广告ID
     */
    public function get_advertise_detail()
    {
        $advertiseId = I('advertise_id');
        $data = D('Advertise')->getAdvertiseDetail($advertiseId);

        ajax_return(1, '广告详情', $data);
    }

    /**
     * 广告商信息
     * @param advertiser_id 广告商ID
     */
    public function get_advertiser_info()
    {
        $advertiserId = I('advertiser_id');
        $data = D('Advertiser')->getAdvertiserInfo($advertiserId);

        ajax_return(1, '广告商信息', $data);
    }
}
