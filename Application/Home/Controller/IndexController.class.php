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

    /**
     * 获取广告商个人信息
     */
    public function get_advertiser()
    {
        $cond['openid'] = I('openid');
        $data = M('advertiser')->where($cond)->find();

        ajax_return(1, '广告商信息', $data);
    }

    /**
     * 更新广告商信息
     */
    public function update_advertiser()
    {
        $ader = D('Advertiser');
        $cond['openid'] = I('openid');
        $ader->create();
        $res = $ader->where($cond)->save();

        if ($res === false) {
            ajax_return(0, '更新广告商失败');
        }
        ajax_return(1);
    }
    /**
     * @param name 更新字段
     * @param value 值
     */
    public function update_advertiser_once()
    {
        $cond['openid'] = I('openid');
        $data[I('name')] = I('value');
        $res = M('advertiser')->where($cond)->save($data);

        if ($res === false) {
            ajax_return(0, '更新广告商失败');
        }
        ajax_return(1);
    }

    /**
     * 登录凭证校验
     * @param js_code 登录凭证code
     */
    public function code_2_session()
    {
        $appid = C('WX_APPID');
        $secret = C('WX_APPSECRET');
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.I('js_code').'&grant_type=authorization_code';
        $info = file_get_contents($url);
        $json = json_decode($info, true);

        $openid = $json['openid'];
        $ader = D('Advertiser');
        $cond = [
            'status' => C('STATUS_Y'),
            'openid' => $openid
        ];
        $aderInfo = $ader->where($cond)->find();
        $aderId = $aderInfo['id'];

        if (!$aderInfo) {
            $ader->create();
            $ader->openid = $openid;
            $aderId = $ader->add();
        }

        $data = [
            'openid'  => $openid,
            'ader_id' => $aderId
        ];

        ajax_return(1, '凭证校验', $data);
    }
}
