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
<ul id="messages"></ul>
<form action="">
    <input id="m" autocomplete="off" /><button>Send</button>
</form>

</body>
<script src="https://cdn.socket.io/socket.io-1.3.7.js"></script>
<script src="http://code.jquery.com/jquery-1.11.1.js"></script>
<script>
    var items = Array('賽在滾','拉屎王','王八烏龜蛋','食屎王','餔雪大師','雪特大師');
    var name = items[Math.floor(Math.random()*items.length)];
    var socket = io('ws://localhost:3000');
    socket.emit('message', name+"加入了廁所不孤單");
    $('form').submit(function(){
        socket.emit('message', name+"說："+$('#m').val());
        $('#m').val('');
        return false;
    });

    socket.on('system', function(data){
//        $('#messages').append($('<li>').text(data));
//        var toilets = JSON.parse(data);
        for(var i=0; i<data.length; i++) {
            $('#messages').append($('<li>').text(data[i].floor));
        }
    });

    socket.on('message', function(msg){
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
