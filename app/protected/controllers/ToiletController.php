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

    /**
     * show intro page
     */
    public function actionIntroduction()
    {
        $this->render('intro');
    }

    /**
     * show live page
     */
    public function actionLive()
    {
        $this->bodyClass = '';
        $this->headerClass = '';

        $toilets = ToiletObj::getToilets();
        $this->render('live', array(
            'toilets' => $toilets
        ));
    }

    /**
     * show chart page
     */
    public function actionCharts()
    {
        $this->bodyClass = '';
        $this->headerClass = '';
        $returnData = $this->getChartData();
        $this->render('charts',
            array(
                'allDataJson'=>json_encode($returnData)
            )
        );
    }

    /**
     * ajax get chart data
     */
    public function actionGetChart()
    {
        $date = $_POST['date'];
        $returnData = $this->getChartData($date);
        echo json_encode($returnData);
    }

    /**
     * get chart data by date (default today)
     * @param string|null $date
     * @return array
     */
    private function getChartData($date=null)
    {
        if(empty($date))
        {
            $date = date('Y/m/d');
        }
        $logs = Yii::app()->db->createCommand()
            ->select('HOUR(created_at) as hour, count(*) as count')
            ->from('toilet_event_logs')
            ->where("spend_time > 1 and to_days(created_at) = to_days('".$date."')")
            ->group('HOUR(created_at)')
            ->queryAll();

        $datas = array();
        $timeArr = array();
        for($i=0; $i<=23; $i++)
        {
            $currentData = array_filter($logs, function($log) use ($i) {
                if($log['hour'] == (string)$i)
                    return $log['count'];
            });

            $datas[$i] = empty($currentData) ? 0 : array_shift($currentData)['count'];
            $timeArr[] = $i."時";
        }

        $returnData = array(
            'datas' => $datas,
            'timeJson' => $timeArr
        );

        return $returnData;
    }

    /**
     * show ori live page
     * @throws CException
     */
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