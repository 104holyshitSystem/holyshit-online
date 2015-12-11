<?php
    class TestController extends CController
    {
        /**
         * 生成log假資料
         */
        public function actionIndex()
        {
            $generateCount = 1000;

            $count = 1;
            while($count <= $generateCount)
            {
                $sensor_command = array('lock','toilet');
                $sensor_value = array('true','false');
                $result = Yii::app()->db->createCommand()
                    ->insert('toilet_event_logs',
                        array(
                            'toilet_id'=>mt_rand(1,2),
                            'sensor_command'=>$sensor_command[mt_rand(0,1)],
                            'sensor_value'=>$sensor_value[mt_rand(0,1)],
                            'unixtime'=>strtotime($this->getRandDateTime()),
                            'created_at'=>$this->getRandDateTime(),
                            'updated_at'=>date('Y-m-d H:i:s'),
                            'spend_time'=>mt_rand(1,1200)
                        )
                    );

                $count++;
            }
            exit;

            //echo strtotime('2015/12/06 00:00:00')."<br />";
            //echo strtotime('2015/12/11 23:59:59')."<br />";
            //$int = mt_rand(1449442800,1449874799);
            //echo date('Y-m-d H:i:s', $int);
            //echo time()."<br />";
            //exit;
        }

        /**
         * 生成亂數時間，排除不可能的時間、離峰時間降低出線機率
         * @return string
         */
        private function getRandDateTime()
        {
            $int = mt_rand(1449356400,1449874799);
            if(date('H:i:s', $int) > '22:00:00' || date('H:i:s', $int) < '08:30:00')
            {
                return $this->getRandDateTime();
            }
            elseif(date('H:i:s', $int) > '18:00:00' || date('H:i:s', $int) < '10:00:00')
            {
                if(mt_rand(-5,1))
                {
                    return $this->getRandDateTime();
                }
            }
            return date('Y-m-d H:i:s', $int);
        }
    }