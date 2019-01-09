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
     * @param type 类型 1：发布 2：收藏
     * @param ader_id 广告商ID
     */
    public function get_advertise_list()
    {
        $type = I('type');

        switch ($type) {
            case '1':
                $cond = [
                    'a.status' => C('STATUS_Y'),
                    'publisher_id' => I('ader_id')
                ];
                break;
            case '2':
                $cond_collect = [
                    'status' => C('STATUS_Y'),
                    'ader_id' => I('ader_id')
                ];
                $collects = M('collect')->where($cond_collect)->getField('ad_id', true);
                $cond = [
                    'a.status' => C('STATUS_Y'),
                    'a.id' => array('in', $collects)
                ];
                break;

            default:
                $cond = [
                    'a.status' => C('STATUS_Y'),
                    'tag_id'   => I('tag_id')
                ];
                break;
        }

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


    ////////
    // 收藏 //
    ////////

    /**
     * 是否收藏
     * @param ad_id 广告ID
     * @param ader_id 广告商ID
     */
    public function is_collect()
    {
        $cond = [
            'ad_id'   => I('ad_id'),
            'ader_id' => I('ader_id'),
            'status'  => C('STATUS_Y')
        ];
        $collect = M('collect')->where($cond)->find();

        if ($collect) {
            $isCollect = 1;
        } else {
            $isCollect = 0;
        }

        ajax_return(1, '是否收藏', $isCollect);
    }

    /**
     * 收藏
     * @param ad_id 广告ID
     * @param ader_id 广告商ID
     */
    public function collect()
    {
        $collect = D('Collect');
        $cond = [
            'ad_id'   => I('ad_id'),
            'ader_id' => I('ader_id')
        ];
        $collectInfo = $collect->where($cond)->find();

        if ($collectInfo) {
            $data = [
                'status' => C('STATUS_Y'),
                'collect_time' => date('Y-m-d H:i:s')
            ];
            $collect->where($cond)->save($data);
        } else {
            $collect->create();
            $collect->add();
        }

        ajax_return(1, '收藏');
    }

    /**
     * 取消收藏
     * @param ad_id 广告ID
     * @param ader_id 广告商ID
     */
    public function cancel_collect()
    {
        $cond = [
            'ad_id'   => I('ad_id'),
            'ader_id' => I('ader_id')
        ];
        $data['status'] = C('STATUS_N');
        M('collect')->where($cond)->save($data);

        ajax_return(1, '取消收藏');
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
     * @return 广告商基本信息
     *         发布广告数量
     *         收藏广告数量
     */
    public function get_advertiser()
    {
        $cond['openid'] = I('openid');
        $data = M('advertiser')->where($cond)->find();
        $data['qrcode'] = C('HOST').'/Uploads/qrcode/'.$data['id'].'.png';

        $aderId = $data['id'];

        // 发布广告数量
        $cond_ad = [
            'status' => C('STATUS_Y'),
            'publisher_id' => $aderId
        ];
        $data['n_ad'] = M('advertise')->where($cond_ad)->count();

        // 收藏广告数量
        $cond_collect = [
            'status' => C('STATUS_Y'),
            'ader_id' => $aderId
        ];
        $data['n_collect'] = M('collect')->where($cond_collect)->count();

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

        $cond = [
            'status' => C('STATUS_Y'),
            'openid' => $openid
        ];
        $aderInfo = D('Advertiser')->where($cond)->find();

        // 注册
        if (!$aderInfo) {
            $ader = D('Advertiser');
            $ader->create();
            $ader->openid = $openid;
            $aderId = $ader->add();

            $aderInfo = $ader->find($aderId);

            // 生成二维码
            $url = C('HOST').'/qrcode?id='.$aderId;
            $path = 'Uploads/qrcode/'.$aderId.'.png';
            qrcodepng($url,$path,6);
        }

        $aderInfo['openid'] = $openid;

        ajax_return(1, '凭证校验', $aderInfo);
    }
}
