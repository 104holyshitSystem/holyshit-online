<?php

/**
 * Created by PhpStorm.
 * User: Apple
 * Date: 15/11/25
 * Time: 下午10:37
 */
class ToiletController extends Controller
{
    public function actionIntroduction()
    {
        $this->render('intro');
    }

    public function actionLive()
    {
        $toilets = Yii::app()->db->createCommand()
            ->select('id, floor, is_door_lock, is_detected_sit_down, updated_at')
            ->from('toilet_realtime_status')
            ->queryAll();

        $this->renderPartial('live', array(
            'toilets' => $toilets
        ));
    }
}