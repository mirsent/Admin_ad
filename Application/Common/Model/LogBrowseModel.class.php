<?php
namespace Common\Model;
use Common\Model\BaseModel;
class LogBrowseModel extends BaseModel{
    protected $_auto=array(
        array('status','get_default_status',1,'callback'),
        array('browse_time','get_time',1,'callback'),
    );

    /**
     * 浏览哪类标签最多
     */
    public function getBrowseMax($cond=[])
    {
        $data = $this
            ->field('tag_id,count(*) as count')
            ->group('tag_id')
            ->order('count desc, browse_time desc')
            ->where(array_filter($cond))
            ->find();
        return $data;
    }
}