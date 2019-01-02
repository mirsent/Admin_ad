<?php
namespace Common\Model;
use Common\Model\BaseModel;
class AdvertiserModel extends BaseModel{
    protected $_auto=array(
        array('status','get_default_status',1,'callback')
    );

    public function getAdvertiserNumber($cond=[])
    {
        $data = $this
            ->where(array_filter($cond))
            ->count();
        return $data;
    }

    public function getAdvertiserData($cond=[])
    {
        $data = $this
            ->where(array_filter($cond))
            ->select();
        return $data;
    }

    public function getAdvertiserInfo($advertiserId)
    {
        $cond['id'] = $advertiserId;
        $data['info'] = $this->where($cond)->find();

        $cond_ad['publisher_id'] = $advertiserId;
        $data['advertises'] = D('Advertise')->getAdvertiseDataApi($cond_ad);

        return $data;
    }
}