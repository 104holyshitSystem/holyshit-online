<?php

/**
 * Created by PhpStorm.
 * User: Apple
 * Date: 15/11/25
 * Time: 下午10:37
 */
class ToiletController extends Controller
{
    public $bodyClass = 'landing';
    public $headerClass = 'alt';
    
    public function actionIntroduction()
    {
        $this->render('intro');
    }

    public function actionLive()
    {
        $this->bodyClass = '';
        $this->headerClass = '';

        $toilets = ToiletObj::getToilets();
        $this->render('live', array(
            'toilets' => $toilets
        ));
    }
    
    public function actionCharts()
    {
        $this->bodyClass = '';
        $this->headerClass = '';
        
        $logs = Yii::app()->db->createCommand()
            ->select('HOUR(created_at) as hour, count(*) as count')
            ->from('toilet_event_logs')
            ->where('spend_time > 1')
            ->group('HOUR(created_at)')
            ->queryAll();

        $datas = array();
        for($i=0; $i<=23; $i++)
        {

            $currentData = array_filter($logs, function($log) use ($i) {
                if($log['hour'] == (string)$i)
                    return $log['count'];
            });

            $datas[$i] = empty($currentData) ? 0 : array_shift($currentData)['count'];
        }
        
        $this->render('charts', array(
            'datas' => json_encode($datas)
        ));
    }

    public function actionLiveTest()
    {
        $toilets = Yii::app()->db->createCommand()
            ->select('id, floor, is_door_lock, is_detected_sit_down, updated_at')
            ->from('toilet_realtime_status')
            ->queryAll();

        $this->renderPartial('live_test', array(
            'toilets' => $toilets
        ));
    }
}