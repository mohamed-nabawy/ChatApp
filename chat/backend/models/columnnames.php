<?php
	class columnnames {
		// add fields
		public $id = ['type' => 'int', 'primary key', 'auto_increment'];
		public $oldColumnName = ['type' => 'string', 'not null', 'unique'];
		public $newColumnName = ['type' => 'string', 'not null', 'unique'];
		public $tableName = ['type' => 'string', 'not null'];
	}

	$columnnames = new columnnames();
	$arr['columnnames'] = $columnnames;