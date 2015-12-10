<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/assets/js/jquery.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/charts/Chart.min.js"></script>

<style>
.all {
	width:100%;
	margin:0 auto;
	font-family:"微軟正黑體", century gothic, verdana, Arial;
	height: 600px;
}
.left {
	width:100%;
	height: 100%;
	background-color: #FFF;
	/*background-image:url(<?php //echo Yii::app()->request->baseUrl; ?>/static/HTML5-UP/images/banner.jpg);*/
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
	background-color:#333;
	line-height:55px;
}

.left-2 {
    width: 70%;
    margin: 5% auto;
}

@media screen and (max-width: 736px) {
	.all {
    	height: 400px;
    }
}

@media screen and (max-width: 620px) {
	.all {
    	height: 250px;
    }
}
</style>
<!-- Main -->
  <article id="main"> 
        <div class="all">
            <div class="left">
              <h3>廁所使用頻度分析圖表</h3>
              <div class="left-2">
				<canvas id="canvas"></canvas>
              </div>
            </div>
        </div>
  </article>
<script>
	var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
	var lineChartData = {
		//labels : ["January","February","March","April","May","June","July"],
		labels : ["0","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23"],
		datasets : [
			{
				label: "My Second dataset",
				fillColor : "rgba(151,187,205,0.2)",
				strokeColor : "rgba(151,187,205,1)",
				pointColor : "rgba(151,187,205,1)",
				pointStrokeColor : "#fff",
				pointHighlightFill : "#fff",
				pointHighlightStroke : "rgba(151,187,205,1)",
				data : <?php echo $datas; ?>
			}
		]
	}

	window.onload = function(){
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myLine = new Chart(ctx).Line(lineChartData, {
			responsive: true
		});
	}
</script>