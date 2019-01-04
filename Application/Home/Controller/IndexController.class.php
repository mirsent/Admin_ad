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

    /**
     * 上传图片
     */
    public function upload_img()
    {
        $path = upload_img('ad');
        echo $path;
    }

    /**
     * 发布广告
     * @param publish_id 发布人
     * @param tag_id 标签ID
     * @param ad_title 标题
     * @param ad_brief 简介
     * @param ad_imgs 图片
     */
    public function add_advertise()
    {
        $ad = D('Advertise');
        $ad->create();
        $res = $ad->add();

        if ($res === false) {
            ajax_return(0, '发布广告失败');
        }
        ajax_return(1);
    }
}
