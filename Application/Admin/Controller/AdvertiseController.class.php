<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
class AdvertiseController extends AdminBaseController{

    //////////
    // 广告标签 //
    //////////

    public function tag(){
        $assign = [
            'table' => 'Tag',
            'name' => 'tag_name',
            'title' => '标签'
        ];
        $this->assign($assign);
        $this->display();
    }

    //////////
    // 广告列表 //
    //////////

    public function ad_list()
    {
        $cond['status'] = C('STATUS_Y');
        $tags = M('tag')->where($cond)->select();

        $assign = compact('tags');
        $this->assign($assign);

        $this->display();
    }

    /**
     * 获取广告信息
     */
    public function get_advertise_info()
    {
        $ms = D('Advertise');
        $cond = [
            'a.status' => C('STATUS_Y')
        ];

        $recordsTotal = $ms->alias('a')->where($cond)->count();

        // 搜索
        $search = I('search');
        if (strlen($search)>0) {
            $cond['tag_name|ad_title|ad_brief|ader_name'] = array('like', '%'.$search.'%');
        }
        $cond['tag_id'] = I('tag_id');
        $cond['ad_title'] = I('ad_title');
        $cond['ader_name'] = I('publisher');
        $searchDate = I('publish_date');
        if (strlen($searchDate)) {
            $cond['publish_time'] = array('between', [$searchDate.' 00:00:00', $searchDate.' 23:59:59']);
        }

        $recordsFiltered = $ms->getAdvertiseNumber($cond);

        // 排序
        $orderObj = I('order')[0];
        $orderColumn = $orderObj['column'];
        $orderDir = $orderObj['dir'];
        if(isset(I('order')[0])){
            $i = intval($orderColumn);
            switch($i){
                case 0: $ms->order('ad_title '.$orderDir); break;
                case 1: $ms->order('ad_brief '.$orderDir); break;
                case 3: $ms->order('ader_name '.$orderDir); break;
                case 4: $ms->order('publish_time '.$orderDir); break;
                case 5: $ms->order('visited '.$orderDir); break;
                default: break;
            }
        } else {
            $ms->order('publish_time desc');
        }

        // 分页
        $start = I('start');
        $limit = I('limit');
        $page = I('page');

        $infos = $ms->page($page, $limit)->getAdvertiseData($cond);

        echo json_encode(array(
            "draw" => intval(I('draw')),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $infos
        ), JSON_UNESCAPED_UNICODE);
    }

    //////////
    // 违规广告 //
    //////////

    public function ad_list_down()
    {
        $cond['status'] = C('STATUS_Y');
        $tags = M('tag')->where($cond)->select();

        $assign = compact('tags');
        $this->assign($assign);

        $this->display();
    }

    /**
     * 获取违规广告信息
     */
    public function get_advertise_down_info()
    {
        $ms = D('Advertise');
        $cond = [
            'a.status' => array('eq', C('STATUS_N'))
        ];

        $recordsTotal = $ms->alias('a')->where($cond)->count();

        // 搜索
        $search = I('search');
        if (strlen($search)>0) {
            $cond['tag_name|ad_title|ad_brief|ader_name'] = array('like', '%'.$search.'%');
        }
        $cond['tag_id'] = I('tag_id');
        $cond['ad_title'] = I('ad_title');
        $cond['ader_name'] = I('publisher');
        $searchDate = I('publish_date');
        if (strlen($searchDate)) {
            $cond['publish_time'] = array('between', [$searchDate.' 00:00:00', $searchDate.' 23:59:59']);
        }

        $recordsFiltered = $ms->getAdvertiseNumber($cond);

        // 排序
        $orderObj = I('order')[0];
        $orderColumn = $orderObj['column'];
        $orderDir = $orderObj['dir'];
        if(isset(I('order')[0])){
            $i = intval($orderColumn);
            switch($i){
                case 0: $ms->order('ad_title '.$orderDir); break;
                case 1: $ms->order('ad_brief '.$orderDir); break;
                case 3: $ms->order('ader_name '.$orderDir); break;
                case 4: $ms->order('publish_time '.$orderDir); break;
                case 5: $ms->order('visited '.$orderDir); break;
                default: break;
            }
        } else {
            $ms->order('publish_time desc');
        }

        // 分页
        $start = I('start');
        $limit = I('limit');
        $page = I('page');

        $infos = $ms->page($page, $limit)->getAdvertiseData($cond);

        echo json_encode(array(
            "draw" => intval(I('draw')),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $infos
        ), JSON_UNESCAPED_UNICODE);
    }

    /**
     * 删除广告
     */
    public function delete_advertise()
    {
        $cond['id'] = I('id');
        $data['status'] = C('STATUS_N');

        $res = M('advertise')->where($cond)->save($data);

        if ($res === false) {
            ajax_return(0, '删除广告失败');
        }
        ajax_return(1);
    }

    /**
     * 恢复广告
     */
    public function restore_advertise()
    {
        $cond['id'] = I('id');
        $data['status'] = C('STATUS_Y');

        $res = M('advertise')->where($cond)->save($data);

        if ($res === false) {
            ajax_return(0, '恢复广告失败');
        }
        ajax_return(1);
    }

}