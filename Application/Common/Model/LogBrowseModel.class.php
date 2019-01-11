<?php
namespace Common\Model;
use Common\Model\BaseModel;
class LogBrowseModel extends BaseModel{
    protected $_auto=array(
        array('status','get_default_status',1,'callback'),
        array('browse_time','get_time',1,'callback'),
    );
}