<?php
/**
 * 廁所資料物件
 * @author edison.huang
 * @version 2015/12/03
 */
class ToiletObj
{
    const SHIT_ON_PATH = '/static/HTML5-UP/images/TT-1041124-MI_Web_03.png';
    const SHIT_OFF_PATH = '/static/HTML5-UP/images/TT-1041124-MI_Web_04.png';
    const ON_PATH = '/static/HTML5-UP/images/TT-1041124-MI_Web_05.png';
    const OFF_PATH = '/static/HTML5-UP/images/TT-1041124-MI_Web_06.png';
    
    public $id;
    public $is_door_lock;
    public $is_detected_sit_down;
    public $floor;
    public $updated_at;
    
    /**
     * 取得ooxx的圖片路徑
     * @return string
     */
    public function getIconPath()
    {
        if($this->is_door_lock && $this->is_detected_sit_down)
        {
            return Yii::app()->request->baseUrl.self::OFF_PATH;
        }
        elseif($this->is_door_lock && !$this->is_detected_sit_down)
        {
            return Yii::app()->request->baseUrl.self::OFF_PATH;
        }
        elseif(!$this->is_door_lock && !$this->is_detected_sit_down)
        {
            return Yii::app()->request->baseUrl.self::ON_PATH;
        }
        elseif(!$this->is_door_lock && !$this->is_detected_sit_down)
        {
            return Yii::app()->request->baseUrl.self::OFF_PATH;
        }
    }
    
    /**
     * 取得廁所狀態的圖片路徑
     * @return string
     */
    public function getShitPath()
    {
        if($this->is_door_lock && $this->is_detected_sit_down)
        {
            return Yii::app()->request->baseUrl.self::SHIT_OFF_PATH;
        }
        elseif($this->is_door_lock && !$this->is_detected_sit_down)
        {
            return Yii::app()->request->baseUrl.self::SHIT_OFF_PATH;
        }
        elseif(!$this->is_door_lock && !$this->is_detected_sit_down)
        {
            return Yii::app()->request->baseUrl.self::SHIT_ON_PATH;
        }
        elseif(!$this->is_door_lock && !$this->is_detected_sit_down)
        {
            return Yii::app()->request->baseUrl.self::SHIT_OFF_PATH;
        }
    }
    
    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->floor = $data['floor'];
        $this->is_door_lock = $data['is_door_lock'];
        $this->is_detected_sit_down = $data['is_detected_sit_down'];
        $this->updated_at = $data['updated_at'];
    }
    
    public static function getToilets()
    {
        //預設資料
        $defaultToilets = self::getDefaultToilets();
        //撈取資料
        $toilets = self::findAll();
        //更新資料
        $toilets = self::getCorrectData($defaultToilets, $toilets);
        //轉換物件
        foreach($toilets as $i => $toilet)
        {
            $toilets[$i] = new ToiletObj($toilet);;
        }
        return $toilets;
    }
    
    public static function findAll()
    {
        $toilets = Yii::app()->db->createCommand()
        ->select('id, floor, is_door_lock, is_detected_sit_down, updated_at')
        ->from('toilet_realtime_status')
        ->queryAll();
        return $toilets;
    }
    
    private static function getCorrectData($defaultToilets, $toilets)
    {
        if(count($toilets) < count($defaultToilets))
        {
            foreach($defaultToilets as $i => $defaultToilet)
            {
                foreach($toilets as $toilet)
                {
                    if($defaultToilet['id'] == $toilet['id'])
                    {
                        $defaultToilets[$i] = $toilet;
                        break;
                    }
                }
            }
        }
        return $defaultToilets;
    }
    
    private static function getDefaultToilets()
    {
        return array(
            array('id'=>1,'floor'=>6,'is_door_lock'=>0,'is_detected_sit_down'=>0,'updated_at'=>'2015/12/12 16:00:00'),
            array('id'=>2,'floor'=>7,'is_door_lock'=>0,'is_detected_sit_down'=>0,'updated_at'=>'2015/12/12 16:00:00'),
        );
    }
}