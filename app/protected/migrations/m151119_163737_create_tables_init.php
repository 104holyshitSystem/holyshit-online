<?php

class m151119_163737_create_tables_init extends CDbMigration
{
	public function safeUp()
	{
		$this->createTable('toilet_realtime_status', array(
			'id'					=>	'pk',
			'is_door_lock'			=>	'TINYINT(1) NULL DEFAULT NULL COMMENT "門已上鎖"',
			'is_detected_sit_down'	=> 	'TINYINT(1) NULL DEFAULT NULL COMMENT "馬桶已佔用"',
			'floor'					=> 	'INTEGER NULL DEFAULT NULL COMMENT "樓層"',
			'created_at'			=>  'datetime COMMENT "建立時間"',
			'updated_at'			=>  'datetime COMMENT "修改時間"',
		),  'COMMENT "廁所即時狀況"');

		$this->createTable('toilet_event_logs', array(
			'id'					=>	'pk',
			'toilet_id'				=>	'INTEGER NULL DEFAULT NULL COMMENT "廁所編號"',
			'sensor_command'		=> 	'VARCHAR(50) NULL DEFAULT NULL COMMENT "觸發事件"',
			'sensor_value'			=> 	'VARCHAR(30) NULL DEFAULT NULL COMMENT "觸發產生的值"',
			'unixtime'				=> 	'INTEGER NULL DEFAULT NULL COMMENT "事件發生當下的unixtime"',
			'created_at'			=>  'datetime COMMENT "建立時間"',
			'updated_at'			=>  'datetime COMMENT "修改時間"',
		),  'COMMENT "廁所事件LOG"');

		$this->execute('CREATE INDEX toilet_event_logs_for_toilet_id on toilet_event_logs(toilet_id)');
		$this->execute('CREATE INDEX toilet_event_logs_for_date_range on toilet_event_logs(toilet_id, sensor_command, sensor_value, unixtime)');

	}


	public function safeDown()
	{
		$this->dropTable('toilet_realtime_status');
		$this->dropTable('toilet_event_logs');
	}
}