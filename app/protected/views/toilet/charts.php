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
    width: 90%;
    margin: 0 auto;
}

@media screen and (max-width: 736px) {
	.all {
    	height: 400px;
    }
	.actions {
		display: none;
	}
}

@media screen and (max-width: 620px) {
	.all {
    	height: 250px;
    }
}

.date {
	border: 1px solid #000000;
	color: black;
	margin: 0;
}

</style>
<!-- Main -->
  <article id="main"> 
        <div class="all">
            <div class="left">
              <h3>廁所使用頻度分析圖表</h3>
				<div style="width:100%;">
					<ul class="actions" style="width:1285px; margin: 15px auto;">
						<?php
						$date = date('Y/m/d');
						$startDate = date('Y/m/d', strtotime('-5 day'));
						$endDate = date('Y/m/d');
						for($i=$startDate ; $i<=$endDate ; $i=date('Y/m/d', strtotime('+1 day', strtotime($i)))){
							$iForUrl = date('Y-m-d', strtotime($i));
						?>
						<li>
							<a class="date button <?php if($i == $date) echo ' special'; ?>" href="javascript:;" onclick="getChart('<?php echo $iForUrl; ?>',$(this));">
								<?php echo $i; ?>
							</a>
						</li>
						<?php } ?>
					</ul>
				</div>
              <div class="left-2">
				<canvas id="canvas" width="1200" height="450"></canvas>
              </div>
            </div>
        </div>
  </article>
<script>
	var lineChartData = getLineChartData(<?php echo $timeJson; ?>, <?php echo $datas; ?>)
	var ctx;
	var myLine;
	window.onload = function(){
		ctx = document.getElementById("canvas").getContext("2d");
		myLine = new Chart(ctx).Line(lineChartData, {
			responsive: true
		});
	}

	function getChart(date, obj)
	{
		if(obj.hasClass('special')) return false;
		$('.button').removeClass('special');
		obj.addClass('special');

		$.ajax({
			url: '<?php echo $this->createUrl('toilet/getChart'); ?>',
			data: 'date='+date,
			type:"POST",
			dataType:'json',
			success: function(msg){
				myLine.destroy();
				lineChartData = getLineChartData(msg.timeJson, msg.datas);
				myLine = new Chart(ctx).Line(lineChartData, {
					responsive: true
				});
			},
			error:function(xhr, ajaxOptions, thrownError){
				alert(xhr.status);
				alert(thrownError);
			}
		});
	}

	function getLineChartData(timeJson, datas)
	{
		var lineChartData = {
			labels : timeJson,
			datasets : [
				{
					label: "My Second dataset",
					fillColor : "rgba(151,187,205,0.2)",
					strokeColor : "rgba(151,187,205,1)",
					pointColor : "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(151,187,205,1)",
					data : datas
				},
			]
		}
		return lineChartData
	}
</script>