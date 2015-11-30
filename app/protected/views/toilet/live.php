<?php
/**
 * Created by PhpStorm.
 * User: Apple
 * Date: 15/11/24
 * Time: 下午10:04
 */
?>

<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Socket.IO chat</title>
    <style>
        .container {
            height: auto;
            overflow: hidden;
        }

        .right {
            width: 30%;
            float: right;
        }

        .left {
            float: none; /* not needed, just for clarification */
            width: auto;
            overflow: hidden;
        }​​

         * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font: 13px Helvetica, Arial; }
        form { background: #000; padding: 3px; position: fixed; bottom: 0; width: 100%; }
        form input { border: 0; padding: 10px; width: 90%; margin-right: .5%; }
        form button { width: 9%; background: rgb(130, 224, 255); border: none; padding: 10px; }
        #messages { list-style-type: none; margin: 0; padding: 0; }
        #messages li { padding: 5px 10px; }
        #messages li:nth-child(odd) { background: #eee; }
    </style>
</head>

<body>
<div class="container">
    <div class="right" id="messages">
        <form action="">
            <input id="m" autocomplete="off" /><button>Send</button>
        </form>
    </div>
    <div class="left" id="toilets">
    </div>
</div>


</body>
<script src="https://cdn.socket.io/socket.io-1.3.7.js"></script>
<script src="http://code.jquery.com/jquery-1.11.1.js"></script>


<script>
    var items = Array('賽在滾','拉屎王','王八烏龜蛋','食屎王','餔雪大師','雪特大師');
    var name = items[Math.floor(Math.random()*items.length)];
    var socket = io('ws://localhost:3000');

    function refreshToilet(data) {
        var notice = "";
        $.each(data, function(i, toilet){
            if(toilet.is_door_lock == true && toilet.is_detected_sit_down == true){
                notice += "<font style='color:red;'>編號[" + toilet.id + "]-樓層[" + toilet.floor + "]的廁所, 正在使用中</font>";
            } else if(toilet.is_door_lock == true && toilet.is_detected_sit_down == false){
                notice += "<font style='color:yellow;'>編號[" + toilet.id + "]-樓層[" + toilet.floor + "]的廁所, 已鎖門, 但沒坐在馬桶</font>";
            } else if(toilet.is_door_lock == false && toilet.is_detected_sit_down == false){
                notice += "<font style='color:green;'>編號[" + toilet.id + "]-樓層[" + toilet.floor + "]的廁所目前是空的</font>";
            } else {
                notice += "編號[" + toilet.id + "]-樓層[" + toilet.floor + "]的廁所使用狀況不正常, 鎖門=" + toilet.is_door_lock + " , 馬桶偵測=" + toilet.is_detected_sit_down;
            }
            console.log(toilet.id + ":" + "is_door_lock=" + toilet.is_door_lock + ", is_detected_sit_down=" + toilet.is_detected_sit_down);
            notice += "-動作時間:" + toilet.updated_at + "<br>";
        });
        $("#toilets").html(notice);
    }

    $(document).on('ready', function() {
        var initData = <?php echo json_encode($toilets); ?>;
        refreshToilet(initData);
    });

    socket.emit('message', name+"加入了廁所不孤單");
    $('form').submit(function(){
        socket.emit('message', name+"說："+$('#m').val());
        $('#m').val('');
        return false;
    });

    socket.on('system', function(data){
        refreshToilet(data);
    });

    socket.on('message', function(msg){
        console.log(msg);
        var myRe = /^command:/;
        if(msg.match(myRe)){
            msg = msg.replace(myRe,'');
            var OBJjson = $.parseJSON(msg);
            var data = OBJjson[0];

            switch(data.command){
                case 'lock':
                    if(data.value=="true"){
                        $('#messages').append($('<li>').text('[系統]廁所'+data.toiletID+'，有人關門了！'));
                    }
                    else{
                        $('#messages').append($('<li>').text('[系統]廁所'+data.toiletID+'，開門了！快去搶！'));
                    }
                    break;
                case 'toilet':
                    if(data.value=="true"){
                        $('#messages').append($('<li>').text('[系統]廁所'+data.toiletID+'，有人坐下了！'));
                    }
                    else{
                        $('#messages').append($('<li>').text('[系統]廁所'+data.toiletID+'，離開馬桶了！快去排隊！'));
                    }
                    break;
                case 'beep':
                    $('#messages').append($('<li>').text('[系統]廁所'+data.toiletID+'，有人拿逼逼卡刷門！卡號是'+data.value));
                    break;
                default:

                    break;
            }

        }
        else{
            $('#messages').append($('<li>').text(msg));
        }

    });
</script>
</html>
