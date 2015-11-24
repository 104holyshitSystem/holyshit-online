<?php

class ApiController extends Controller
{
	public function actionLive()
	{
		$toilets = Yii::app()->db->createCommand()
			->select('id, is_door_lock, is_detected_sit_down, updated_at')
			->from('toilet_realtime_status')
			->queryAll();

		header('Content-Type: application/json');
		exit(json_encode($toilets));
	}

	public function actionSend()
	{
		if(isset($_POST['jsondata']))
		{

			$transaction = Yii::app()->db->beginTransaction();

			try {

				$eventParams = json_decode($_POST['jsondata'], true)[0];

				$eventLog = new ToiletEventLogs;
				$eventLog->attributes = array(
					'toilet_id' 		=> $eventParams['toiletID'],
					'sensor_command' 	=> $eventParams['command'],
					'sensor_value'		=> $eventParams['value'],
					'unixtime'			=> $eventParams['unixtime'],
				);

				if(!$eventLog->save())
					throw new CDbException($eventLog->getErrors());

				$toilet = Toilet::model()->findByPk($eventParams['toiletID']);
				if(isset($toilet))
				{

					switch ($eventParams['command'])
					{
						case 'toilet':
							$toilet->is_detected_sit_down = $this->_toBoolean($eventParams['value']);
							break;
						case 'lock':
							$toilet->is_door_lock = $this->_toBoolean($eventParams['value']);
							break;
						case 'beep':
							// RFID ?????
							break;
						default:
							throw new Exception("非法command[{$eventParams['command']}]");

					}

					$toilet->updated_at = date('Y-m-d H:i:s', $eventParams['unixtime']);
					if(!$toilet->save())
						throw new CDbException($toilet->getErrors());
				}
				else
				{
					$toilet = new Toilet;
					$toilet->attributes = array(
						'id' => $eventParams['toiletID'],
						'is_door_lock' => $eventParams['command'] == 'lock' ? $this->_toBoolean($eventParams['value']) : false,
						'is_detected_sit_down' => $eventParams['command'] == 'toilet' ? $this->_toBoolean($eventParams['value']) : false,
						'updated_at' => date('Y-m-d H:i:s', $eventParams['unixtime']),
					);

					if(!$toilet->save())
						throw new CDbException($toilet->getErrors());
				}

				$transaction->commit();

			} catch (CDbException $dbException) {
				$transaction->rollback();
			}  catch (Exception $e) {
				$transaction->rollback();
			}
		}
	}

	private function _toBoolean($value)
	{
		return strtolower($value) === 'true';
	}
}