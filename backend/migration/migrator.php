<?php
	class migrator {
		public function up($conn, $upFunc) {
			$upFunc($conn);
		}

		public function down($conn, $downFunc) {
			$downFunc($conn);
		}
	}