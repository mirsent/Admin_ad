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
            $cond['advertiser|location|brief|advertiser'] = array('like', '%'.$search.'%');
        }
        $cond['advertiser'] = I('advertiser');
        $cond['tel'] = I('tel');
        if (strlen($searchDate)) {
            $cond['publish_time'] = array('between', [$searchDate.' 00:00:00', $searchDate.' 23:59:59']);
        }

        $recordsFiltered = $ms->getAdvertiserNumber($cond);

        // 排序
        $orderObj = I('order')[0];
        $orderColumn = $orderObj['column'];
        $orderDir = $orderObj['dir'];
        if(isset(I('order')[0])){
            $i = intval($orderColumn);
            switch($i){
                case 1: $ms->order('advertiser '.$orderDir); break;
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

}