<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/assets/js/jquery.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/socketio/socket.io-1.3.7.js"></script>

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
	var items = Array('真·','武聖·','超·','霸皇·','炫空·','爆裂·','霹靂·');
    var items2 = Array('后里蟹','拉屎王','蛋營養','蹲廁所','我屎故我在','原力屎');
    var name = items[Math.floor(Math.random()*items.length)]+items2[Math.floor(Math.random()*items.length)];
    var socket = io('ws://localhost:3000');
    
    var oo = '<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/images/TT-1041124-MI_Web_03.png';
    var xx = '<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/images/TT-1041124-MI_Web_04.png';
    var done = '<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/images/TT-1041124-MI_Web_07.png';
    var o = '<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/images/TT-1041124-MI_Web_05.png';
    var x = '<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/images/TT-1041124-MI_Web_06.png';

    socket.emit('message', name+"加入了廁所不孤單");
    $('form').submit(function(){
        var msg = $.trim($('#m').val());
        if(msg != ''){
            socket.emit('message', name+"說："+msg);
        }
        $('#m').val('');
        return false;
    });

	var lockCheck = new Array();
	var shitCheck = new Array();
	<?php 
	foreach($toilets as $toilet){ 
		echo "lockCheck['".$toilet->id."'] = ".$toilet->getIsDoorLock().";".PHP_EOL;
		echo "shitCheck['".$toilet->id."'] = ".$toilet->getIsDetectedSitDown().";".PHP_EOL;
	}
	?>
	var shitDone = false;
    var status = xx;
    var statusIcon = x;
    socket.on('message', function(msg){
    	shitDone = false;        
        console.log(msg);
        var myRe = /^command:/;
        if(msg.match(myRe)){
            msg = msg.replace(myRe,'');
            var OBJjson = $.parseJSON(msg);
            var data = OBJjson[0];
	    var toiletname = (data.toiletID==1)?'6F':'7F';
            switch(data.command){
                case 'lock':
                    if(data.value=="true"){
                        $('#messages').append($('<li>').text('[系統]廁所'+toiletname+'，有人關門了！'));
                        lockCheck[data.toiletID] = true;
                    }else{
                        $('#messages').append($('<li>').text('[系統]廁所'+toiletname+'，開門了！快去搶！'));
                        lockCheck[data.toiletID] = false;
                    }
                    break;
                case 'toilet':
                    if(data.value=="true"){
                        $('#messages').append($('<li>').text('[系統]廁所'+toiletname+'，有人坐下了！'));
                        shitCheck[data.toiletID] = true;
                    }else{
                        $('#messages').append($('<li>').text('[系統]廁所'+toiletname+'，離開馬桶了！快去排隊！'));
                        shitCheck[data.toiletID] = false;
                        if(lockCheck[data.toiletID] == true) shitDone = true;
                    }
                    break;
		        case 'bathHOT':
                    if(data.value=="true"){
                        $('#messages').append($('<li>').text('[系統]廁所'+toiletname+'，有人靠近廁所門！'));
                    }
                    break;
				case 'warning':
                    if(data.value=="on"){
                        $('#messages').append($('<li>').text('[系統]廁所'+toiletname+'，戰況緊急，需要支援！'));
                    }
                    break;
                case 'beep':
                    $('#messages').append($('<li>').text('[系統]廁所'+toiletname+'，有人拿逼逼卡刷門！卡號是'+data.value));
                    break;
                default:
                break;
            }
            if(lockCheck[data.toiletID] == true && shitCheck[data.toiletID] == true){
            	status = xx;
            	statusIcon = x;
            }else if(lockCheck[data.toiletID] == true && shitCheck[data.toiletID] == false){
                status = xx;
            	statusIcon = x;
            	if(shitDone) status = done;
            }else if(lockCheck[data.toiletID] == false && shitCheck[data.toiletID] == false){
            	status = oo;
            	statusIcon = o;
            }else if(lockCheck[data.toiletID] == false && shitCheck[data.toiletID] == true){
            	status = xx;
            	statusIcon = x;
            }
            $("#toilet-"+data.toiletID).attr('src', status);
            $("#toilet-status-"+data.toiletID).attr('src', statusIcon);
        }else{
            $('#messages').append($('<li>').text(msg));
        }
        
        $("#messages").scrollTop( 999999999 );
    });
</script>
