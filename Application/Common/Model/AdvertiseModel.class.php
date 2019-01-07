<?php
namespace Common\Model;
use Common\Model\BaseModel;
class AdvertiseModel extends BaseModel{

    protected $_auto=array(
        array('status','get_default_status',1,'callback'),
        array('publish_time','get_time',1,'callback'),
        array('visited','0',1,'string')
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
            ->field('a.*, tag_name, ader_name')
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
            ->field('a.id, tag_id, ad_title, visited, SUBSTRING(publish_time, 12, 5) as publish_time, SUBSTRING(publish_time, 1, 16) as publish_datetime, tag_name, ader_name')
            ->where(array_filter($cond))
            ->select();
        return $data;
    }

    public function getAdvertiseDetail($adId)
    {
        $cond['a.id'] = $adId;

        $this->where(['id'=>$adId])->setInc('visited');

        $data = $this
            ->alias('a')
            ->join('__TAG__ t ON t.id = a.tag_id')
            ->join('__ADVERTISER__ ar ON ar.id = a.publisher_id')
            ->field('a.*, SUBSTRING(publish_time, 1, 16) as publish_time, tag_name, ader_name')
            ->where(array_filter($cond))
            ->find();

        if ($data['ad_imgs']) {
            $data['ad_imgs_arr'] = explode(',', $data['ad_imgs']);
        }

        return $data;
    }
}
