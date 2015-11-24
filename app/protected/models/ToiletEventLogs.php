<?php

/**
 * This is the model class for table "toilet_event_logs".
 *
 * The followings are the available columns in table 'toilet_event_logs':
 * @property integer $id
 * @property integer $toilet_id
 * @property string $sensor_command
 * @property string $sensor_value
 * @property integer $unixtime
 * @property string $created_at
 * @property string $updated_at
 */
class ToiletEventLogs extends CActiveRecord
{
	public function tableName()
	{
		return 'toilet_event_logs';
	}

	public function rules()
	{
		return array(
			array('toilet_id, sensor_command, sensor_value, unixtime', 'required'),
			array('sensor_command', 'length', 'max'=>50),
			array('sensor_value', 'length', 'max'=>30),
			array('created_at, updated_at', 'default', 'value' => new CDbExpression('NOW()'), 'setOnEmpty'=>false, 'on' => 'insert'),
			array('updated_at', 'default', 'value' => new CDbExpression('NOW()'), 'setOnEmpty'=>false, 'on' => 'update'),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, toilet_id, sensor_command, sensor_value, unixtime, created_at, updated_at', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'toilet_id' => '廁所編號',
			'sensor_command' => '觸發事件',
			'sensor_value' => '觸發產生的值',
			'unixtime' => '事件發生當下的unixtime',
			'created_at' => '建立時間',
			'updated_at' => '修改時間',
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('toilet_id',$this->toilet_id);
		$criteria->compare('sensor_command',$this->sensor_command,true);
		$criteria->compare('sensor_value',$this->sensor_value,true);
		$criteria->compare('unixtime',$this->unixtime);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
