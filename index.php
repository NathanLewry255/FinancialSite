<?php ob_start(); session_start(); include "Backend/phpFunctions.php"; if(!isset($_SESSION['loggedIn'])){header("Location: Pages/login.php");}?>
<html>
	<head>
		<title>FINANCE WEBSITE V1</title>
		<link rel="stylesheet" href="Stylesheet/Stylesheet.css">
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@200;600;700&display=swap" rel="stylesheet">
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
		var balanceChartDanielPWeek = [];
		var balanceChartNathanPWeek = [];
		var balanceChartDanielPYear = [];
		var balanceChartNathanPYear = [];
		
		var today = new Date();
		
		google.charts.load('current', {'packages':['corechart', 'bar']});
		google.charts.setOnLoadCallback(drawWeeklyExpenditureCharts);
		google.charts.setOnLoadCallback(drawMonthlyExpenditureCharts);

		if (document.addEventListener) {
			window.addEventListener('resize', drawWeeklyExpenditureCharts);
			window.addEventListener('resize', drawMonthlyExpenditureCharts);
		}
		else if (document.attachEvent) {
			window.attachEvent('onresize', drawWeeklyExpenditureCharts);
			window.attachEvent('onresize', drawMonthlyExpenditureCharts);
		}
		else {
			window.resize = drawWeeklyExpenditureCharts;
			window.resize = drawMonthlyExpenditureCharts;
		}
		
		function CreateLineGraphData(records){
			var data = new google.visualization.DataTable();
			data.addColumn('date', 'Date');
			data.addColumn('number', 'Income');
			data.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
			data.addColumn('number', 'Expenditure');
			data.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
			data.addRows(records);
			return data;
		}
		
		Date.prototype.GetSixWeeksAgo = function() {
			console.log(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDay() - ((6 * 7)-9)));
			return (new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDay() - ((6 * 7)-9)));
		}
		Date.prototype.GetToday = function() {
			return (new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDay()));
		}
		
		Date.prototype.GetFirstDayOfYear = function() {
			return (new Date(new Date().getFullYear(), 0, 1));
		}
		Date.prototype.GetLastDayOfYear = function() {
			return (new Date(new Date().getFullYear(), 11, 31));
		}
		
		function drawWeeklyExpenditureCharts() {
			var options = {
				pointSize: 8,
				tooltip: {isHtml: true},
				legend: {position: 'none'},
				lineWidth: 5,
				backgroundColor: '#4a4a4a',
				hAxis: {
					minValue: today.GetSixWeeksAgo(),
					maxValue: today.GetToday(),
					baselineColor: '#858585',
					format: 'dd/MM/yy',
					gridlines: {
						count: 7,
						color: '#858585'
					},
					minorGridlines: {
						count: 1
					},
					textStyle: {
						color: '#ffffff',
						fontSize: 15,
						fontName: 'Source Sans Pro',
						bold: false,
						italic: false
					},
					titleTextStyle: {
						color: '#ffffff',
						fontSize: 15,
						fontName: 'Source Sans Pro',
						bold: false,
						italic: false
					}
				},
				vAxis: {
					baselineColor: '#858585',
					format: '$#.##',
					gridlines: {
						count: 14,
						color: '#858585'
					},
					minorGridlines: {
						count: 1
					},
					textStyle: {
						color: '#ffffff',
						fontSize: 15,
						fontName: 'Source Sans Pro',
						bold: false,
						italic: false
					},
					titleTextStyle: {
						color: '#ffffff',
						fontSize: 15,
						fontName: 'Source Sans Pro',
						bold: false,
						italic: false
					}
				},
				colors: ['#B6F76B', '#FF3650']
			};

			var chart = new google.visualization.LineChart(document.getElementById('balanceChartDanielWeekly'));
			chart.draw(CreateLineGraphData(balanceChartDanielPWeek), options);
			
			var chart = new google.visualization.LineChart(document.getElementById('balanceChartNathanWeekly'));
			chart.draw(CreateLineGraphData(balanceChartNathanPWeek), options);
		}
		
		function drawMonthlyExpenditureCharts() {
			var options = {
				pointSize: 8,
				tooltip: {isHtml: true},
				legend: {position: 'none'},
				lineWidth: 5,
				backgroundColor: '#4a4a4a',
				hAxis: {
					minValue: today.GetFirstDayOfYear(),
					maxValue: today.GetLastDayOfYear(),
					baselineColor: '#858585',
					format: 'dd/MM/yy',
					gridlines: {
						count: 24,
						color: '#858585'
					},
					minorGridlines: {
						count: 24
					},
					textStyle: {
						color: '#ffffff',
						fontSize: 15,
						fontName: 'Source Sans Pro',
						bold: false,
						italic: false
					},
					titleTextStyle: {
						color: '#ffffff',
						fontSize: 15,
						fontName: 'Source Sans Pro',
						bold: false,
						italic: false
					}
				},
				vAxis: {
					baselineColor: '#858585',
					format: '$#.##',
					gridlines: {
						count: 14,
						color: '#858585'
					},
					minorGridlines: {
						count: 14
					},
					textStyle: {
						color: '#ffffff',
						fontSize: 15,
						fontName: 'Source Sans Pro',
						bold: false,
						italic: false
					},
					titleTextStyle: {
						color: '#ffffff',
						fontSize: 15,
						fontName: 'Source Sans Pro',
						bold: false,
						italic: false
					}
				},
				colors: ['#B6F76B', '#FF3650']
			};

			var chart = new google.visualization.LineChart(document.getElementById('balanceChartDanielMonthly'));
			chart.draw(CreateLineGraphData(balanceChartDanielPYear), options);
			
			var chart = new google.visualization.LineChart(document.getElementById('balanceChartNathanMonthly'));
			chart.draw(CreateLineGraphData(balanceChartNathanPYear), options);
		}
		</script>
		<?php 
			//PER WEEK
			for($i = 0; $i < 7; $i++){
				if(isset($recordsArray)){unset($recordsArray);}
				$recordsArray = array();
				GetAccountSumByGroupToArray("Daniel", "recordDate >= '".date("Y-m-d", strtotime(''.$i.' week', strtotime("-6 weeks")))."' AND recordDate < '".date("Y-m-d", strtotime(''.($i + 1).' week', strtotime("-6 weeks")))."'", "DATE_FORMAT(recordDate, '%Y-%m-01')", "incomeSum;expendSum;recordDate", $recordsArray, $i);
				$date = date("Y-m-d", strtotime(''.$i.' week', strtotime("-6 weeks")));
				if(count($recordsArray) == 0){
					echo "<script>balanceChartDanielPWeek.push([new Date('".$date."T00:00:00+1200'), 0, '<span style=\"color: #B6F76B; font-size: 18px;\">".date('Y-m-d', strtotime($date))." WK".$i.": </span><span style=\"color: #B6F76B; font-size: 18px;\">+$0.00</span> <span style=\"color: #FF3650; font-size: 18px;\">-$0.00</span>', 0,'<span style=\"color: #B6F76B; font-size: 18px;\">".date('Y-m-d', strtotime($date))." WK".$i.": </span><span style=\"color: #B6F76B; font-size: 18px;\">+$0.00</span> <span style=\"color: #FF3650; font-size: 18px;\">-$0.00</span>'])</script>";
				}
				else{
					for($j = 0; $j < count($recordsArray); $j++){
						echo "<script>balanceChartDanielPWeek.push([new Date('".$date."T00:00:00+1200'), ".$recordsArray[$j][0].", '<span style=\"color: #B6F76B; font-size: 18px;\">".date('Y-m-d', strtotime($date))." WK".$i.": </span><span style=\"color: #B6F76B; font-size: 18px;\">' + '+$".number_format($recordsArray[$j][0], 2)."</span> <span style=\"color: #FF3650; font-size: 18px;\">' + '-$".str_replace("-", "", number_format($recordsArray[$j][1], 2))."</span>', Math.abs(".$recordsArray[$j][1]."),'<span style=\"color: #B6F76B; font-size: 18px;\">".date('Y-m-d', strtotime($date))." WK".$i.": </span><span style=\"color: #B6F76B; font-size: 18px;\">' + '+$".number_format($recordsArray[$j][0], 2)."</span> <span style=\"color: #FF3650; font-size: 18px;\">' + '-$".str_replace("-", "", number_format($recordsArray[$j][1], 2))."</span>'])</script>";
					}
				}
			}
			
			for($i = 0; $i < 7; $i++){
				if(isset($recordsArray)){unset($recordsArray);}
				$recordsArray = array();
				GetAccountSumByGroupToArray("Nathan", "recordDate >= '".date("Y-m-d", strtotime(''.$i.' week', strtotime("-6 weeks")))."' AND recordDate < '".date("Y-m-d", strtotime(''.($i + 1).' week', strtotime("-6 weeks")))."'", "DATE_FORMAT(recordDate, '%Y-%m-01')", "incomeSum;expendSum;recordDate", $recordsArray, $i);
				$date = date("Y-m-d", strtotime(''.$i.' week', strtotime("-6 weeks")));
				if(count($recordsArray) == 0){
					echo "<script>balanceChartNathanPWeek.push([new Date('".$date."T00:00:00+1200'), 0, '<span style=\"color: #B6F76B; font-size: 18px;\">".date('Y-m-d', strtotime($date))." WK".$i.": </span><span style=\"color: #B6F76B; font-size: 18px;\">+$0.00</span> <span style=\"color: #FF3650; font-size: 18px;\">-$0.00</span>', 0,'<span style=\"color: #B6F76B; font-size: 18px;\">".date('Y-m-d', strtotime($date))." WK".$i.": </span><span style=\"color: #B6F76B; font-size: 18px;\">+$0.00</span> <span style=\"color: #FF3650; font-size: 18px;\">-$0.00</span>'])</script>";
				}
				else{
					for($j = 0; $j < count($recordsArray); $j++){
						echo "<script>balanceChartNathanPWeek.push([new Date('".$date."T00:00:00+1200'), ".$recordsArray[$j][0].", '<span style=\"color: #B6F76B; font-size: 18px;\">".date('Y-m-d', strtotime($date))." WK".$i.": </span><span style=\"color: #B6F76B; font-size: 18px;\">' + '+$".number_format($recordsArray[$j][0], 2)."</span> <span style=\"color: #FF3650; font-size: 18px;\">' + '-$".str_replace("-", "", number_format($recordsArray[$j][1], 2))."</span>', Math.abs(".$recordsArray[$j][1]."),'<span style=\"color: #B6F76B; font-size: 18px;\">".date('Y-m-d', strtotime($date))." WK".$i.": </span><span style=\"color: #B6F76B; font-size: 18px;\">' + '+$".number_format($recordsArray[$j][0], 2)."</span> <span style=\"color: #FF3650; font-size: 18px;\">' + '-$".str_replace("-", "", number_format($recordsArray[$j][1], 2))."</span>'])</script>";
					}
				}
			}
			
			//PER YEAR
			$recordsArray = array();
			GetAccountSumByGroupToArrayYear("Daniel", "recordDate > '".date("Y-m-d", strtotime("first day of January"))."' AND recordDate < '".date("Y-m-d", strtotime("last day of December"))."'", "DATE_FORMAT(recordDate, '%Y-%m-01')", "incomeSum;expendSum;recordDate", $recordsArray);
			$dayInc = 0;
			for($i = 0; $i < count($recordsArray); $i++){
				$date = date('Y-m', strtotime(''.$dayInc.' month', strtotime('first day of January')));
				while($date < date($recordsArray[$i][2])){
					echo "<script>balanceChartDanielPYear.push([new Date('".$date."T00:00:00+1200'), 0, '<span style=\"color: #B6F76B; font-size: 18px;\">".$date.": </span><span style=\"color: #B6F76B; font-size: 18px;\">+$0.00</span> <span style=\"color: #FF3650; font-size: 18px;\">-$0.00</span>', 0,'<span style=\"color: #B6F76B; font-size: 18px;\">".$date.": </span><span style=\"color: #B6F76B; font-size: 18px;\">+$0.00</span> <span style=\"color: #FF3650; font-size: 18px;\">-$0.00</span>'])</script>";
					$dayInc++;
					$date = date('Y-m', strtotime(''.$dayInc.' month', strtotime('first day of January')));
				}
				$date = date($recordsArray[$i][2]);
				echo "<script>balanceChartDanielPYear.push([new Date('".$recordsArray[$i][2]."T00:00:00+1200'), ".$recordsArray[$i][0].", '<span style=\"color: #B6F76B; font-size: 18px;\">".$recordsArray[$i][2].": </span><span style=\"color: #B6F76B; font-size: 18px;\">' + '+$".number_format($recordsArray[$i][0], 2)."</span> <span style=\"color: #FF3650; font-size: 18px;\">' + '-$".str_replace("-", "", number_format($recordsArray[$i][1], 2))."</span>', Math.abs(".$recordsArray[$i][1]."),'<span style=\"color: #B6F76B; font-size: 18px;\">".$recordsArray[$i][2].": </span><span style=\"color: #B6F76B; font-size: 18px;\">' + '+$".number_format($recordsArray[$i][0], 2)."</span> <span style=\"color: #FF3650; font-size: 18px;\">' + '-$".str_replace("-", "", number_format($recordsArray[$i][1], 2))."</span>'])</script>";
				$dayInc++;
			}
			
			$recordsArray = array();
			GetAccountSumByGroupToArrayYear("Nathan", "recordDate > '".date("Y-m-d", strtotime("first day of January"))."' AND recordDate < '".date("Y-m-d", strtotime("last day of December"))."'", "DATE_FORMAT(recordDate, '%Y-%m-01')", "incomeSum;expendSum;recordDate", $recordsArray);
			$dayInc = 0;
			for($i = 0; $i < count($recordsArray); $i++){
				$date = date('Y-m', strtotime(''.$dayInc.' month', strtotime('first day of January')));
				while($date < date($recordsArray[$i][2])){
					echo "<script>balanceChartNathanPYear.push([new Date('".$date."T00:00:00+1200'), 0, '<span style=\"color: #B6F76B; font-size: 18px;\">".$date.": </span><span style=\"color: #B6F76B; font-size: 18px;\">+$0.00</span> <span style=\"color: #FF3650; font-size: 18px;\">-$0.00</span>', 0,'<span style=\"color: #B6F76B; font-size: 18px;\">".$date.": </span><span style=\"color: #B6F76B; font-size: 18px;\">+$0.00</span> <span style=\"color: #FF3650; font-size: 18px;\">-$0.00</span>'])</script>";
					$dayInc++;
					$date = date('Y-m', strtotime(''.$dayInc.' month', strtotime('first day of January')));
				}
				$date = date($recordsArray[$i][2]);
				echo "<script>balanceChartNathanPYear.push([new Date('".$recordsArray[$i][2]."T00:00:00+1200'), ".$recordsArray[$i][0].", '<span style=\"color: #B6F76B; font-size: 18px;\">".$recordsArray[$i][2].": </span><span style=\"color: #B6F76B; font-size: 18px;\">' + '+$".number_format($recordsArray[$i][0], 2)."</span> <span style=\"color: #FF3650; font-size: 18px;\">' + '-$".str_replace("-", "", number_format($recordsArray[$i][1], 2))."</span>', Math.abs(".$recordsArray[$i][1]."),'<span style=\"color: #B6F76B; font-size: 18px;\">".$recordsArray[$i][2].": </span><span style=\"color: #B6F76B; font-size: 18px;\">' + '+$".number_format($recordsArray[$i][0], 2)."</span> <span style=\"color: #FF3650; font-size: 18px;\">' + '-$".str_replace("-", "", number_format($recordsArray[$i][1], 2))."</span>'])</script>";
				$dayInc++;
			}
		?>
	</head>
	<body>
		<div class="NavBar">
			<span id="Title"><a href="index.php">FINANCIAL SITE</a></span>
			<span id="NavLink"><a href="Pages/finances.php">FINANCES</a></span>
			<span id="NavLink"><a href="Pages/tags.php">TAGS</a></span>
		</div>
		<div class="PageBodyWrapper">
			<div class="PageBodyHalfCell IndexCell" style="flex: 0 0 calc(50%-80px);">
				<h1>&#x1F95E; DANIEL</h1>
				<table class="HomePageRunDown">
					<tr>
						<th><h2>Current Balance:</h2></th>
						<th>
							<h2>
							<?php
								$result = GetAccountSum("Daniel", "");
								if($result >= 0){
									echo "<span style='color:#B6F76B;'>$".number_format($result,2)."</span>";
								} 
								else{
									echo "<span style='color:#FF3650;'>-$".str_replace("-", "", number_format($result,2))."</span>";
								}
							?>
							</h2>
						</th>
					</tr>
					<tr>
						<th style='color:#B6F76B;'><br>This Week:</th>
						<th style='color:#B6F76B;'><br><?php echo date("d/m/y", strtotime("monday this week"))." - ".date("d/m/y", strtotime("sunday this week")); ?></th>
					</tr>
					<tr>
						<th>Income: </th>
						<th>
						<?php
							$result = GetAccountSum("Daniel", "recordAmount > 0 AND recordDate > ".date("Y-m-d", strtotime("monday this week"))."");
							echo "$".number_format($result,2)."";
						?>
						</th>
					</tr>
					<tr>
						<th>Expenditure:</th>
						<th>
						<?php
							$result = GetAccountSum("Daniel", "recordAmount < 0 AND recordDate > ".date("Y-m-d", strtotime("monday this week"))."");
							echo "$".str_replace("-", "", number_format($result,2))."";
						?>
						</th>
					</tr>
					<tr>
						<th><br>Spending Target:</th>
						<th><br>$200.00</th>
					</tr>
					<tr>
						<th>Distance from Target:</th>
						<th>
						<?php
							$result = GetAccountSum("Daniel", "recordAmount < 0 AND recordDate > ".date("Y-m-d", strtotime("monday this week"))."");
							$result += 200;
							if($result >= 0){
								echo "<span style='color:#B6F76B;'>+$".number_format($result,2)."</span>";
							} 
							else{
								echo "<span style='color:#FF3650;'>-$".str_replace("-", "", number_format($result,2))."</span>";
							}
						?></th>
					</tr>
				</table>
			</div>
			<div class="PageBodyHalfCell IndexCell" style="flex: 0 0 calc(50%-80px);">
				<h1>&#x1F355; NATHAN</h1>
				<table class="HomePageRunDown">
					<tr>
						<th><h2>Current Balance:</h2></th>
						<th>
							<h2>
							<?php 
								$result = GetAccountSum("Nathan", "");
								if($result >= 0){
									echo "<span style='color:#B6F76B;'>$".number_format($result,2)."</span>";
								} 
								else{
									echo "<span style='color:#FF3650;'>-$".str_replace("-", "", number_format($result,2))."</span>";
								}
							?>
							</h2>
						</th>
					</tr>
					<tr>
						<th style='color:#B6F76B;'><br>This Week:</th>
						<th style='color:#B6F76B;'><br><?php echo date("d/m/y", strtotime("monday this week"))." - ".date("d/m/y", strtotime("sunday this week")); ?></th>
					</tr>
					<tr>
						<th>Income: </th>
						<th>
						<?php
							$result = GetAccountSum("Nathan", "recordAmount > 0 AND recordDate > ".date("Y-m-d", strtotime("monday this week"))."");
							echo "$".number_format($result,2)."";
						?>
						</th>
					</tr>
					<tr>
						<th>Expenditure:</th>
						<th>
						<?php
							$result = GetAccountSum("Nathan", "recordAmount < 0 AND recordDate > ".date("Y-m-d", strtotime("monday this week"))."");
							echo "$".str_replace("-", "", number_format($result,2))."";
						?>
						</th>
					</tr>
					<tr>
						<th><br>Spending Target:</th>
						<th><br>$200.00</th>
					</tr>
					<tr>
						<th>Distance from Target:</th>
						<th>
						<?php
							$result = GetAccountSum("Nathan", "recordAmount < 0 AND recordDate > ".date("Y-m-d", strtotime("last monday"))."");
							$result += 200;
							if($result >= 0){
								echo "<span style='color:#B6F76B;'>+$".number_format($result,2)."</span>";
							} 
							else{
								echo "<span style='color:#FF3650;'>-$".str_replace("-", "", number_format($result,2))."</span>";
							}
						?></th>
					</tr>
				</table>
			</div>
			<div class="PageBodyHalfCell IndexCell" style="flex: 0 0 calc(50%-80px);">
				<h1>INCOME V. EXPENDITURE</h1>
				<h2>PER WEEK / LAST SIX WEEKS</h2>
				<div id="balanceChartDanielWeekly" style="flex: 0 0 calc(50%-80px); height: 400px;"></div>
				<h2>PER MONTH / THIS YEAR</h2>
				<div id="balanceChartDanielMonthly" style="flex: 0 0 calc(50%-80px); height: 400px;"></div>
			</div>
			<div class="PageBodyHalfCell IndexCell" style="flex: 0 0 calc(50%-80px);">
				<h1>INCOME V. EXPENDITURE</h1>
				<h2>PER WEEK / LAST SIX WEEKS</h2>
				<div id="balanceChartNathanWeekly" style="flex: 0 0 calc(50%-80px); height: 400px;"></div>
				<h2>PER MONTH / THIS YEAR</h2>
				<div id="balanceChartNathanMonthly" style="flex: 0 0 calc(50%-80px); height: 400px;"></div>
			</div>
			<!--<div class="PageBodyHalfCell IndexCell" style="flex: 0 0 calc(50%-80px);">
				<h1>EXPENDITURE VIA TAG</h1>
				<div id="expenditureTagDaniel" style="flex: 0 0 calc(50%-80px); height: 400px;"></div>
			</div>
			<div class="PageBodyHalfCell IndexCell" style="flex: 0 0 calc(50%-80px);">
				<h1>EXPENDITURE VIA TAG</h1>
				<div id="expenditureTagNathan" style="flex: 0 0 calc(50%-80px); height: 400px;"></div>
			</div>
			<div class="PageBodyHalfCell IndexCell" style="flex: 0 0 calc(50%-80px);">
				<h1>DELTA FROM TARGET</h1>
			</div>
			<div class="PageBodyHalfCell IndexCell" style="flex: 0 0 calc(50%-80px);">
				<h1>DELTA FROM TARGET</h1>
			</div>-->
			<div style="margin: 10px;"></div>
		</div>
	</body>
</html>
<?php ob_end_flush(); ?>