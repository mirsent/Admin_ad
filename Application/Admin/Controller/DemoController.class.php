<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
class DemoController extends AdminBaseController{

    public function get_demo_info(){
        $ms = D('User');
        $cond = [
            'status' => C('STATUS_Y')
        ];

        $recordsTotal = $ms->where($cond)->count(); // 没有过滤的记录数

        // 搜索
        $search = I('search');
        $userName = I('user_name');
        if (strlen($search)>0) {
            $cond['real_name|user_name'] = array('like', '%'.$search.'%');
        }
        if (strlen($userName)>0) $cond['user_name'] = $userName;

        $recordsFiltered = $ms->where($cond)->count(); // 过滤后的记录数

        // 排序
        $orderObj = I('order')[0];
        $orderColumn = $orderObj['column']; // 排序列，从0开始
        $orderDir = $orderObj['dir'];       // ase desc
        if(isset(I('order')[0])){
            $i = intval($orderColumn);
            switch($i){
                case 0: $ms->order('real_name '.$orderDir); break;
                case 1: $ms->order('user_name '.$orderDir); break;
                default: break;
            }
        }

        // 分页
        $start = I('start');  // 开始的记录序号
        $limit = I('limit');  // 每页显示条数
        $page = I('page');    // 第几页

        $infos = $ms->where($cond)->page($page, $limit)->select();

        echo json_encode(array(
            "draw" => intval(I('draw')),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $infos
        ), JSON_UNESCAPED_UNICODE);
    }

    /**
     * 上传会员等级图片
     */
    public function upload(){
        $imgUrl = upload_img('img');

        ajax_return(1,'上传图片成功',$imgUrl);
    }
}
