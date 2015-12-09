<?php

class m151209_111208_add_column_spend_time_to_event_log extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('toilet_event_logs', 'spend_time', 'integer');
	}

	public function safeDown()
	{
		$this->dropColumn('toilet_event_logs', 'spend_time');
	}
}