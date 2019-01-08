<?php
namespace Common\Model;
use Common\Model\BaseModel;
class CollectModel extends BaseModel{
    protected $_auto=array(
        array('status','get_default_status',1,'callback'),
        array('collect_time','get_time',1,'callback'),
    );

    public function getCollectNumber($cond=[])
    {
        $data = $this
            ->alias('c')
            ->join('__ADVERTISE__ ad ON ad.id = c.ad_id')
            ->join('__ADVERTISER__ ader ON ader.id = c.ader_id')
            ->where(array_filter($cond))
            ->count();
        return $data;
    }

    public function getCollectData($cond=[])
    {
        $data = $this
            ->alias('c')
            ->join('__ADVERTISE__ ad ON ad.id = c.ad_id')
            ->join('__ADVERTISER__ ader ON ader.id = c.ader_id')
            ->field('c.*,ad.ad_title,ader.nickname')
            ->where(array_filter($cond))
            ->select();
        return $data;
    }
}