<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
class AdvertiserController extends AdminBaseController{

    /**
     * 获取广告商信息
     */
    public function get_advertiser_info()
    {
        $ms = D('Advertiser');
        $cond = [
            'status' => C('STATUS_Y')
        ];

        $recordsTotal = $ms->where($cond)->count();

        // 搜索
        $search = I('search');
        if (strlen($search)>0) {
            $cond['ader_name|location|brief|service'] = array('like', '%'.$search.'%');
        }
        $cond['ader_name'] = I('ader_name');
        $cond['tel'] = I('tel');

        $searchDate = I('register_date');
        if (strlen($searchDate)) {
            $cond['register_time'] = array('between', [$searchDate.' 00:00:00', $searchDate.' 23:59:59']);
        }

        $recordsFiltered = $ms->getAdvertiserNumber($cond);

        // 排序
        $orderObj = I('order')[0];
        $orderColumn = $orderObj['column'];
        $orderDir = $orderObj['dir'];
        if(isset(I('order')[0])){
            $i = intval($orderColumn);
            switch($i){
                case 1: $ms->order('ader_name '.$orderDir); break;
                case 2: $ms->order('location '.$orderDir); break;
                case 3: $ms->order('tel '.$orderDir); break;
                case 4: $ms->order('service '.$orderDir); break;
                case 5: $ms->order('register_time '.$orderDir); break;
                default: break;
            }
        } else {
            $ms->order('register_time desc');
        }

        // 分页
        $start = I('start');
        $limit = I('limit');
        $page = I('page');

        $infos = $ms->page($page, $limit)->getAdvertiserData($cond);

        echo json_encode(array(
            "draw" => intval(I('draw')),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $infos
        ), JSON_UNESCAPED_UNICODE);
    }

    /**
     * 删除广告商
     */
    public function delete_advertiser()
    {
        $cond['id'] = I('id');
        $data['status'] = C('STATUS_N');
        $res = M('advertiser')->where($cond)->save($data);

        if ($res === false) {
            ajax_return(0, '删除广告商失败');
        }
        ajax_return(1);
    }

    /**
     * 获取广告收藏信息
     */
    public function get_collection_info()
    {
        $ms = D('Collect');
        $cond = [
            'c.status' => C('STATUS_Y')
        ];

        $recordsTotal = $ms->alias('c')->where($cond)->count();

        // 搜索
        $search = I('search');
        if (strlen($search)>0) {
            $cond['ad_title|nickname'] = array('like', '%'.$search.'%');
        }
        $cond['ad_title'] = I('ad_title');
        $cond['nickname'] = I('nickname');

        $searchDate = I('collect_time');
        if (strlen($searchDate)) {
            $cond['collect_time'] = array('between', [$searchDate.' 00:00:00', $searchDate.' 23:59:59']);
        }

        $recordsFiltered = $ms->getCollectNumber($cond);

        // 排序
        $orderObj = I('order')[0];
        $orderColumn = $orderObj['column'];
        $orderDir = $orderObj['dir'];
        if(isset(I('order')[0])){
            $i = intval($orderColumn);
            switch($i){
                case 1: $ms->order('ad_title '.$orderDir); break;
                case 2: $ms->order('nickname '.$orderDir); break;
                case 3: $ms->order('collect_time '.$orderDir); break;
                default: break;
            }
        } else {
            $ms->order('collect_time desc');
        }

        // 分页
        $start = I('start');
        $limit = I('limit');
        $page = I('page');

        $infos = $ms->page($page, $limit)->getCollectData($cond);

        echo json_encode(array(
            "draw" => intval(I('draw')),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $infos
        ), JSON_UNESCAPED_UNICODE);
    }

}