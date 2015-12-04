﻿<script src="https://cdn.socket.io/socket.io-1.3.7.js"></script>
<script src="http://code.jquery.com/jquery-1.11.1.js"></script>

<style>
.all {
	width:100%;
	margin:0 auto;
	font-family:"微軟正黑體", century gothic, verdana, Arial;
	height: 600px;
}
.left {
	float:left;
	width:50%;
	height: 100%;
	background-image:url(<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/images/banner.jpg);
}
.right {
	float: right;
	width:50%;
	background-color: #fff;
	height: 100%;
}
.left h3 {
	color:#FFF;
	font-size:20px;
	font-weight: 100;
	width:100%;
	padding:0;
	margin:0 auto;
	text-align:center;
	height:55px;
	background-color:#444;
	line-height:55px;
}
.right h4 {
	color:#FFF;
	font-size:20px;
	font-weight: 100;
	text-align:center;
	width:100%;
	padding:0;
	margin:0 auto;
	height:55px;
	background-color:#333;
	line-height:55px;
	margin-bottom:20px;
}
.left li {
	margin:0 auto;
	height:100%;
	background: rgba(100%,100%,100%,0.6);
	width:95%;
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
	text-align:center;
	list-style-type:none;
	margin-left:8px;
	margin-top: 30px;
	margin-bottom:20px;
	padding: 15px 0;
}

.left .left-2 li span {
	font-size:70px;
	vertical-align: middle;
	font-weight: bold;
	color:#333;
	margin-left:-5%;
}

.left .left-2 li img {
	vertical-align: middle;
}
.left .left-2 li strong img {
	width: 38%;
	margin-right: 10%;
	margin-left: 10%;
}

.right .right-2 {
	padding:0 10px;
	overflow: auto;
	height: 440px;
	margin:0px 30px 10px 30px;
}
.right .name {
	color:#F39;
	padding:0;
	margin:5px 0;
	font-weight:bold;
}
.right span {
	color:#666666;
	margin:0px;
	padding-left: 5px;
}
.right-3 {
	text-align:left;
	line-height:30px;
	margin:0px 30px 10px 30px;
	border:#555 solid 8px;
}
.right-3 input{
	color: #000;
}

#messages { list-style-type: none; color: #000; }
#messages li:nth-child(odd) { background: #eee; }

@media screen and (max-width: 1100px) {
	.left .left-2 li span {
	font-size:45px;
	}
}
@media screen and (max-width: 980px) {
	.left .left-2 li span {
	font-size:35px;
	}
}
@media screen and (max-width: 736px) {
    .left {
    	width:100%;
    }
    .right {
    	width:100%;
    }
    .left .left-2 li span {
	font-size:35px;
	}
}
</style>
<!-- Main -->
  <article id="main"> 
        <div class="all">
            <div class="left">
              <h3>男廁使用狀態顯示</h3>
              <div class="left-2">
                <ul>
                  <?php foreach($toilets as $toilet){ ?>
                  <li>
                    <span><?php echo $toilet->floor; ?>F</span>
                    <strong>
                        <img id="toilet-<?php echo $toilet->id; ?>" src="<?php echo $toilet->getShitPath(); ?>" />
                    </strong>
                    <img id="toilet-status-<?php echo $toilet->id; ?>" src="<?php echo $toilet->getIconPath(); ?>" />
                  </li>
                  <?php } ?>
                </ul>
              </div>
            </div>
            <div class="right">
              <h4>聊天室</h4>
              <ul class="right-2" id="messages"></ul>
              <!-- 
              <div class="right-2">
                <div>
			     <span class="name">使用者1</span><span>大家好你是你是你是你是你是你是你是你是你是你是你是你是你是你是你是你是你是你是你是</span>
			    </div>
              </div>
              -->
              <div class="right-3">
              	<form style="margin:0; padding:0">
                    <input style="width: 100%" id="m" autocomplete="off" placeholder="Type here..." />
                </form>
              </div>
          </div>
        </div>
  </article>

<script>
    var items = Array('賽在滾','拉屎王','王八烏龜蛋','食屎王','餔雪大師','雪特大師');
    var name = items[Math.floor(Math.random()*items.length)];
    var socket = io('ws://localhost:3000');
    
    var oo = '<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/images/TT-1041124-MI_Web_03.png';
    var xx = '<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/images/TT-1041124-MI_Web_04.png';
    var o = '<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/images/TT-1041124-MI_Web_05.png';
    var x = '<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/images/TT-1041124-MI_Web_06.png';
    
    function refreshToilet(data) {
        //var notice = "";
        $.each(data, function(i, toilet){
            if(toilet.is_door_lock == true && toilet.is_detected_sit_down == true){
                //notice += "<font style='color:red;'>編號[" + toilet.id + "]-樓層[" + toilet.floor + "]的廁所, 正在使用中</font>";
            	$("#toilet-"+toilet.id).attr('src', xx);
            	$("#toilet-status-"+toilet.id).attr('src', x);
            } else if(toilet.is_door_lock == true && toilet.is_detected_sit_down == false){
                //notice += "<font style='color:yellow;'>編號[" + toilet.id + "]-樓層[" + toilet.floor + "]的廁所, 已鎖門, 但沒坐在馬桶</font>";
            	$("#toilet-"+toilet.id).attr('src', xx);
            	$("#toilet-status-"+toilet.id).attr('src', x);
            } else if(toilet.is_door_lock == false && toilet.is_detected_sit_down == false){
                //notice += "<font style='color:green;'>編號[" + toilet.id + "]-樓層[" + toilet.floor + "]的廁所目前是空的</font>";
            	$("#toilet-"+toilet.id).attr('src', oo);
            	$("#toilet-status-"+toilet.id).attr('src', o);
            } else {
                //notice += "編號[" + toilet.id + "]-樓層[" + toilet.floor + "]的廁所使用狀況不正常, 鎖門=" + toilet.is_door_lock + " , 馬桶偵測=" + toilet.is_detected_sit_down;
            	$("#toilet-"+toilet.id).attr('src', xx);
            	$("#toilet-status-"+toilet.id).attr('src', x);
            }
            console.log(toilet.id + ":" + "is_door_lock=" + toilet.is_door_lock + ", is_detected_sit_down=" + toilet.is_detected_sit_down);
            //notice += "-動作時間:" + toilet.updated_at + "<br>";
        });
        //$("#toilets").html(notice);
    }

    <?php /* ?>
    $(document).on('ready', function() {
        var initData = <?php echo json_encode($toilets); ?>;
        refreshToilet(initData);
    });
    <?php */ ?>
    
    socket.emit('message', name+"加入了廁所不孤單");
    $('form').submit(function(){
        var msg = $.trim($('#m').val());
        if(msg != ''){
            socket.emit('message', name+"說："+msg);
        }
        $('#m').val('');
        return false;
    });

    socket.on('system', function(data){
        refreshToilet(data);
    });

    var lockCheck = false;
    var shitCheck = false;
    var status = xx;
    var statusIcon = x;
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
                        lockCheck = true;
                    }
                    else{
                        $('#messages').append($('<li>').text('[系統]廁所'+data.toiletID+'，開門了！快去搶！'));
                        lockCheck = false;
                    }
                    break;
                case 'toilet':
                    if(data.value=="true"){
                        $('#messages').append($('<li>').text('[系統]廁所'+data.toiletID+'，有人坐下了！'));
                        shitCheck = true;
                    }
                    else{
                        $('#messages').append($('<li>').text('[系統]廁所'+data.toiletID+'，離開馬桶了！快去排隊！'));
                        shitCheck = false;
                    }
                    break;
		        case 'bathHOT':
                    if(data.value=="true"){
                        $('#messages').append($('<li>').text('[系統]廁所'+data.toiletID+'，有人靠近廁所門！'));
                        shitCheck = false;
                    }
                    break;
                case 'beep':
                    $('#messages').append($('<li>').text('[系統]廁所'+data.toiletID+'，有人拿逼逼卡刷門！卡號是'+data.value));
                    break;
                default:
                break;
            }
            if(lockCheck == true && shitCheck == true){
            	status = xx;
            	statusIcon = x;
            }else if(lockCheck == true && shitCheck == false){
            	status = xx;
            	statusIcon = x;
            }else if(lockCheck == false && shitCheck == false){
            	status = oo;
            	statusIcon = o;
            }else if(lockCheck == false && shitCheck == true){
            	status = xx;
            	statusIcon = x;
            }
            $("#toilet-"+data.toiletID).attr('src', status);
            $("#toilet-status-"+data.toiletID).attr('src', statusIcon);
        }
        else{
            $('#messages').append($('<li>').text(msg));
        }
        
        $("#messages").scrollTop( 999999999 );
    });
</script>