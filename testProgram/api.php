<?php

/**
 * @see http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers
 * @see http://php.net/manual/en/book.pdo.php
 * @see http://code.tutsplus.com/tutorials/why-you-should-be-using-phps-pdo-for-database-access--net-12059
 */

# MySQL with PDO_MYSQL
$host = 'localhost';
$dbname = 'holyshits';
$user = 'root';
$pass = '';
$pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


try {

    // [{"version":"1.4","toiletID":1,"command":"toilet","value":"true","unixtime":1447857156}]

    $pdo->beginTransaction();

    $eventParams = json_decode($_POST['jsondata'], true)[0];

    // var_dump($eventParams);exit;

    $toiletId = (int)$eventParams['toiletID'];
    $sensorCommand = $eventParams['command'];
    $sensorValue = $eventParams['value'];
    $unixtime = $eventParams['unixtime'];
    $createdAt = date('Y-m-d H:i:s');

    $eventSql = $pdo->prepare("INSERT INTO toilet_events(toilet_id, sensor_command, sensor_value, unixtime, created_at) VALUES (:toilet_id, :sensor_command, :sensor_value, :unixtime, :created_at)");
    $eventSql->bindValue(':toilet_id', $toiletId, PDO::PARAM_STR);
    $eventSql->bindValue(':sensor_command', $sensorCommand, PDO::PARAM_STR);
    $eventSql->bindValue(':sensor_value', $sensorValue, PDO::PARAM_STR);
    $eventSql->bindValue(':unixtime', $unixtime, PDO::PARAM_STR);
    $eventSql->bindValue(':created_at', $createdAt, PDO::PARAM_STR);
    $eventSql->execute();


    $toiletSql = $pdo->prepare('SELECT * FROM toilet_realtime_status WHERE id = :id');
    $toiletSql->bindValue(':id', $toiletId, PDO::PARAM_INT);
    $toiletSql->execute();
    $toilet = $toiletSql->fetchAll(PDO::FETCH_OBJ);

    if(!empty($toilet))
    {
        $updateAttribute = '';

        switch ($sensorCommand)
        {
            case 'toilet':
                $updateAttribute = 'is_detected_sit_down';
                break;
            case 'lock':
                $updateAttribute = 'is_door_lock';
                break;

            default:
                throw new Exception('非法command'.$sensorCommand);

        }

        $updateToiletSql = $pdo->prepare("UPDATE toilet_realtime_status SET {$updateAttribute}=:sensor_value, updated_at=:updated_at WHERE id=:id");
        $updateToiletSql->bindValue(':sensor_value', toBoolean($sensorValue), PDO::PARAM_INT);
        $updateToiletSql->bindValue(':updated_at', date('Y-m-d H:i:s', $unixtime), PDO::PARAM_STR);
        $updateToiletSql->bindValue(':id', $toiletId, PDO::PARAM_INT);
        $updateToiletSql->execute();

        if($updateToiletSql->rowCount() == 0)
            throw new Exception("沒有記錄被update:\n".$updateToiletSql->debugDumpParams());

    }
    else
    {
        $insertToiletSql = $pdo->prepare("INSERT INTO toilet_realtime_status(id, is_door_lock, is_detected_sit_down, floor, updated_at) VALUES(:id, :is_door_lock, :is_detected_sit_down, :floor, :updated_at)");
        $insertToiletSql->bindValue(':id', $toiletId, PDO::PARAM_INT);
        $insertToiletSql->bindValue(':is_door_lock', $sensorCommand, PDO::PARAM_STR);
        $insertToiletSql->bindValue(':is_detected_sit_down', $sensorValue, PDO::PARAM_STR);
        $insertToiletSql->bindValue(':floor', $currentFloor = 0, PDO::PARAM_INT);
        $insertToiletSql->bindValue(':updated_at', date('Y-m-d H:i:s', $unixtime), PDO::PARAM_STR);
        $insertToiletSql->execute();

        if($insertToiletSql->rowCount() == 0)
            throw new Exception("沒有記錄被insert:\n".$insertToiletSql->debugDumpParams());
    }

    $pdo->commit();

} catch(PDOException $e) {
    echo $e->getMessage();
    $pdo->rollBack();
}


function toBoolean($string)
{
    if($string === 'true')
        return 1;

    return 0;
}