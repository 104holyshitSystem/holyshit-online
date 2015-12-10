/**
 * Created by Apple on 15/11/24.
 */

var app   = require("express")();
var mysql = require("mysql");
var http  = require('http').Server(app);
var io    = require("socket.io")(http);
var querystring = require('querystring');
var bodyParser = require('body-parser');

/**
 * expressjs req.body undefined
 * @see http://stackoverflow.com/questions/9177049/express-js-req-body-undefined
 */
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));


var db = mysql.createPool({
    connectionLimit   :   100,
    host              :   'localhost',
    user              :   'root',
    password          :   '',
    database          :   'holyshit',
    debug             :   false
});

/**
 * @see https://github.com/felixge/node-mysql
 * @see https://codeforgeek.com/2015/03/real-time-app-socket-io/
 * @see http://stackoverflow.com/questions/847185/convert-a-unix-timestamp-to-time-in-javascript
 * @see http://stackoverflow.com/questions/5818312/mysql-with-node-js
 * @see http://www.sitepoint.com/using-node-mysql-javascript-client/
 * @see http://www.codexpedia.com/javascript/nodejs-mysql-crud-example/
 * @see https://www.terlici.com/2015/08/13/mysql-node-express.html
 * @see http://www.codediesel.com/nodejs/mysql-transactions-in-nodejs/
 * @see https://cnodejs.org/topic/516b64596d38277306407936
 * @see http://stackoverflow.com/questions/263965/how-can-i-convert-a-string-to-boolean-in-javascript
 * @param sensorCommand
 * @param callback
 */
var addEvent = function(sensorCommand, callback) {
    db.getConnection(function(error, connection) {
        if(error) {
            connection.release();
            callback(false);
            return;
        }

        var toiletId = sensorCommand.toiletID;
        var command = sensorCommand.command;
        var sensorValue = sensorCommand.value;
        var createdAt = new Date();
        var updatedAt = new Date(sensorCommand.unixtime * 1000);
        var spendTime = sensorCommand.taketime;

        connection.beginTransaction(function(error) {
            connection.query(
                "INSERT INTO toilet_event_logs(toilet_id, sensor_command, sensor_value, unixtime, created_at, updated_at, spend_time) VALUES (?, ?, ?, ?, ?, ?, ?)",
                [toiletId, command, sensorValue, sensorCommand.unixtime, createdAt, updatedAt, spendTime],
                function(error, results, fields) {
                    if(error) {
                        connection.rollback();
                        console.log("新增event發生錯誤:" + error);
                        callback(false);
                        return;
                    }

                    connection.query(
                        "SELECT * FROM toilet_realtime_status WHERE id = ?",
                        [toiletId],
                        function(error, results, fields) {
                            if(error) {
                                connection.rollback();
                                console.log("讀取即時資訊發生錯誤:" + error);
                                callback(false);
                                return;
                            }

                            var statusScopes = ["toilet", "lock"];

                            if(statusScopes.indexOf(command) == -1) {
                                connection.commit();
                                console.log(command + '不更新廁所動態顯示表.');
                                callback(true);
                                return;
                            }

                            if(results.length > 0) {

                                var updateAttribute = '';
                                if(command == 'lock') {
                                    updateAttribute = 'is_door_lock';
                                } else if (command == 'toilet') {
                                    updateAttribute = 'is_detected_sit_down';
                                } else {
                                    connection.commit();
                                    console.log(command + ', 不是一個廁所是否使用的狀態值');
                                    callback(true);
                                    return;
                                }

                                connection.query(
                                    "UPDATE toilet_realtime_status SET " + updateAttribute + " = ?, updated_at = ? WHERE id = ?",
                                    [(sensorValue == "true") ? 1 : 0, updatedAt, toiletId],
                                    function(error, results, fields) {
                                        if(error) {
                                            connection.rollback();
                                            console.log("更新即時資訊發生錯誤:" + error);
                                            callback(false);
                                            return;
                                        }
                                    }
                                );

                            } else {

                                var floor = (toiletId == "1") ? 6 : 7;

                                connection.query(
                                    "INSERT INTO toilet_realtime_status(id, is_door_lock, is_detected_sit_down, floor, created_at, updated_at) VALUES(?, ?, ?, ?, ?, ?)",
                                    [
                                        toiletId,
                                        (command == 'lock') ? Boolean(sensorValue) : 0,
                                        (command == 'toilet') ? Boolean(sensorValue) : 0,
                                        floor,
                                        createdAt,
                                        updatedAt
                                    ],
                                    function(error, results, fields) {
                                        if(error) {
                                            connection.rollback();
                                            console.log("新增即時資訊發生錯誤:" + error);
                                            callback(false);
                                            return;
                                        }
                                    }
                                );
                            }

                            connection.commit();
                            console.log('資料已存入');
                            callback(true);
                        }
                    );
                }
            );
        });

        connection.on('error', function (error) {
            console.log(error);
            callback(false);
            return;
        });

        connection.release();
    });
}

// 改成直接回應 200
app.get("/",function(req, res){
    res.send(200);
});


// 接收參數 並且廣播給連線的使用者
app.post('/api',function(req, res){
    var commandParams = JSON.parse(req.body.jsondata)[0];
    console.log(commandParams);

    addEvent(commandParams, function(isSuccess) {
        if(isSuccess) {
            db.getConnection(function(error, connection) {
                if (error) {
                    connection.release();
                    callback(false);
                    return;
                }

                connection.query("SELECT * FROM toilet_realtime_status", function(error, rows, fields) {
                    if(error) {
                        console.log(error);
                    }

                    // 2015.12.10 system頻道先註解掉, 目前沒用到了
                    // io.emit('system', rows);
                    io.emit('message',  'command:' + req.body.jsondata);

                });

                connection.release();

            });
        }
    });

    res.send(200);
});

/**
 * 隨機選定名稱
 * @see http://socket.io/get-started/chat/
 */
io.on('connection', function(socket){
    socket.on('message', function(msg){
        io.emit('message', msg);
    });
});

http.listen(3000,function(){
    console.log("Listening on 3000");
});
