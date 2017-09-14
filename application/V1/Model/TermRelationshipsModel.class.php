<?php
namespace V1\Model;

use Common\Model\CommonModel;

class TermRelationshipsModel extends CommonModel {

    protected $tableName = 'v1_gallery_term_relationships';


	protected function _before_write(&$data) {
		parent::_before_write($data);
	}

}