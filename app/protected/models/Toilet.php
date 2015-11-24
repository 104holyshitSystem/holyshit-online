<?php

/**
 * This is the model class for table "toilet_realtime_status".
 *
 * The followings are the available columns in table 'toilet_realtime_status':
 * @property integer $id
 * @property integer $is_door_lock
 * @property integer $is_detected_sit_down
 * @property integer $floor
 * @property string $created_at
 * @property string $updated_at
 */
class Toilet extends CActiveRecord
{
	public function tableName()
	{
		return 'toilet_realtime_status';
	}

	public function rules()
	{
		return array(
			array('id, is_door_lock, is_detected_sit_down, floor, updated_at', 'safe'),
			array('created_at, updated_at', 'default', 'value' => new CDbExpression('NOW()'), 'setOnEmpty'=>false, 'on' => 'insert'),
			array('updated_at', 'default', 'value' => new CDbExpression('NOW()'), 'setOnEmpty'=>false, 'on' => 'update'),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, is_door_lock, is_detected_sit_down, floor, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'is_door_lock' => '門已上鎖',
			'is_detected_sit_down' => '馬桶已佔用',
			'floor' => '樓層',
			'created_at' => '建立時間',
			'updated_at' => '修改時間',
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('is_door_lock',$this->is_door_lock);
		$criteria->compare('is_detected_sit_down',$this->is_detected_sit_down);
		$criteria->compare('floor',$this->floor);
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
