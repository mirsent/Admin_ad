<?php
namespace Common\Model;
use Common\Model\BaseModel;
class AdvertiseModel extends BaseModel{

    protected $_auto=array(
        array('status','get_default_status',1,'callback')
    );

    public function getAdvertiseNumber($cond=[])
    {
        $data = $this
            ->alias('a')
            ->join('__TAG__ t ON t.id = a.tag_id')
            ->join('__ADVERTISER__ ar ON ar.id = a.publisher_id')
            ->where(array_filter($cond))
            ->count();
        return $data;
    }

    public function getAdvertiseData($cond=[])
    {
        $data = $this
            ->alias('a')
            ->join('__TAG__ t ON t.id = a.tag_id')
            ->join('__ADVERTISER__ ar ON ar.id = a.publisher_id')
            ->field('a.*, tag_name, advertiser')
            ->where(array_filter($cond))
            ->select();
        return $data;
    }

    public function getAdvertiseDataApi($cond=[])
    {
        $data = $this
            ->alias('a')
            ->join('__TAG__ t ON t.id = a.tag_id')
            ->join('__ADVERTISER__ ar ON ar.id = a.publisher_id')
            ->field('tag_id, title, visited, SUBSTRING(publish_time, 12, 5) as publish_time, tag_name, advertiser')
            ->where(array_filter($cond))
            ->select();
        return $data;
    }

    public function getAdvertiseDetail($adId)
    {
        $cond['a.id'] = $adId;
        $data = $this
            ->alias('a')
            ->join('__TAG__ t ON t.id = a.tag_id')
            ->join('__ADVERTISER__ ar ON ar.id = a.publisher_id')
            ->field('a.*, SUBSTRING(publish_time, 1, 16) as publish_time, tag_name, advertiser')
            ->where(array_filter($cond))
            ->select();
        return $data;
    }
}
