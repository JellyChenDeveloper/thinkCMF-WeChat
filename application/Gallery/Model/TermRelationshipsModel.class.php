<?php
namespace Gallery\Model;

use Common\Model\CommonModel;

class TermRelationshipsModel extends CommonModel {

    protected $tableName = 'gallery_term_relationships';


	protected function _before_write(&$data) {
		parent::_before_write($data);
	}

}