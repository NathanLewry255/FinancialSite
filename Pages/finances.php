<?php ob_start(); session_start(); include "../Backend/phpFunctions.php"; if(!isset($_SESSION['loggedIn'])){header("Location: ../Pages/login.php");}?>
<html>
	<head>
		<title>FINANCE WEBSITE V1</title>
		<link rel="stylesheet" href="../Stylesheet/Stylesheet.css">
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@200;600;700&display=swap" rel="stylesheet">
	</head>
	<script>
		var depositArray = [];

		Date.prototype.toDateInputValue = (function() {
			var local = new Date(this);
			local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
			return local.toJSON().slice(0,10);
		});
		
		function ResetDateField(){
			document.getElementById("recordDate").value = new Date().toDateInputValue();
		}
		
		function ResetCheckboxes(){
			var TagList = document.getElementById("tagScrollableList");
			for(var i = 0; i < TagList.children.length; i++){
				TagList.children[i].checked = false;
			}
		}
		
		function UpdateEditSettings(recordID){
			if(recordID == 0){
				ResetDateField();
				ResetCheckboxes();
				document.getElementById("AddEditRecordTitle").innerHTML = "ADD RECORD";
				document.getElementById("recordID").value = "";
				document.getElementById("recordAmount").value = "";
				document.getElementById("recordAccount").selectedIndex = 0;
				document.getElementById("recordInfo").value = "";
				document.getElementById("recordSubmitButton").value = "CREATE RECORD";
				return;
			}
			for(var i = 0; i < depositArray.length; i++){
				if(depositArray[i][0] == recordID){
					document.getElementById("AddEditRecordTitle").innerHTML = "EDIT RECORD: " + depositArray[i][0];
					document.getElementById("recordDate").value = new Date(depositArray[i][1]).toDateInputValue();
					document.getElementById("recordID").value = depositArray[i][0];
					document.getElementById("recordAmount").value = depositArray[i][2];
					if(depositArray[i][3] == "Nathan"){
						document.getElementById("recordAccount").selectedIndex = 1;
					}
					else{
						document.getElementById("recordAccount").selectedIndex = 0;
					}
					document.getElementById("recordInfo").value = depositArray[i][4];
					document.getElementById("recordSubmitButton").value = "UPDATE RECORD";
					
					ResetCheckboxes();
					var tmpTagStr = ""+depositArray[i][5];
					var tmpTagSplit = tmpTagStr.split(";");
					for(var j = 0; j < tmpTagSplit.length; j++){
						var tmpTagIDSplit = tmpTagSplit[j].split(",");
						if(typeof document.getElementById("tagCB"+tmpTagIDSplit[0]) != null && tmpTagIDSplit[0] != ""){
							document.getElementById("tagCB"+tmpTagIDSplit[0]).checked = true;
						}
					}
					return;
				}
			}
		}
		
		function SwitchToFilter(filterSwitch){
			if(!filterSwitch){
				document.getElementById("addRecord").style.display = "block";
				document.getElementById("addRecordTab").style.backgroundColor = "#4a4a4a";
				document.getElementById("filterRecord").style.display = "none";
				document.getElementById("filterRecordTab").style.backgroundColor = "#2e2e2e";
				
			}
			else{
				document.getElementById("addRecord").style.display = "none";
				document.getElementById("addRecordTab").style.backgroundColor = "#2e2e2e";
				document.getElementById("filterRecord").style.display = "block";
				document.getElementById("filterRecordTab").style.backgroundColor = "#4a4a4a";
			}
		}
		
		function PushDepositToArray(depositID, depositDate, depositAmount, depositAccount, depositInfo, depositTags){
			var tmp = [depositID, depositDate, depositAmount, depositAccount, depositInfo, depositTags];
			depositArray.push(tmp);
		}
	</script>
	<body onload="UpdateEditSettings(0); SwitchToFilter(false);">
		<div class="NavBar">
			<span id="Title"><a href="../index.php">FINANCIAL SITE</a></span>
			<span id="NavLink"><a href="finances.php">FINANCES</a></span>
			<span id="NavLink"><a href="tags.php">TAGS</a></span>
		</div>
		<div class="PageBodyWrapper">
			<div class="PageBodySidebarBody" id="test" style="height: 800px;">
				<h1>FINANCES</h1>
				<div class="ScrollableList">
					<table class="DepositPageTable">
					<tr><th style='padding-right:20px;'></th><th style="color: #B6F76B;"><h2>DATE</h2></th><th style="color: #B6F76B;"><h2>AMOUNT</h2></th><th style="color: #B6F76B;"><h2>ACCOUNT</h2></th><th style="color: #B6F76B;"><h2>INFO</h2></th><th style="color: #B6F76B;"><h2>TAGS</h2></th></tr>
					<?php						
						$recordArray = array();
						GetFinancesWithTags($recordArray, "recordID;recordDate;recordAmount;recordAccount;recordInfo;tagID;tagName;tagColour");
						for($i = 0; $i < count($recordArray); $i++){
							$date = date_create($recordArray[$i][1]);
							$tags = explode(';', $recordArray[$i][5]);
							
							echo "<script>PushDepositToArray(".$recordArray[$i][0].",'".$recordArray[$i][1]."',".$recordArray[$i][2].",'".$recordArray[$i][3]."','".$recordArray[$i][4]."','".$recordArray[$i][5]."');</script>";
							
							if($recordArray[$i][2] > 0){
								echo "<tr><th style='padding-right:20px;'><form action='../Backend/recordUpdate.php' method='post' style='display:inline; margin-right: 20px;' onclick='return confirm(&quot;Are you sure you want to delete this entry?&quot;);'><input name='recordID' value='".$recordArray[$i][0]."' hidden></input><input class='RecordEditDeleteButton' type='submit' name='recordDeleteButton' value='×' style='color: #FF3650;'></form><button class='RecordEditDeleteButton' onclick='UpdateEditSettings(".$recordArray[$i][0].");'>+</button></th><th>" .date_format($date, "d/m/y"). "</th><th style='color: #B6F76B;'>+$".number_format($recordArray[$i][2], 2)."</th><th>".$recordArray[$i][3]."</th><th style='max-width: 200px;'>".$recordArray[$i][4]."</th>";//<th><span class='Test' style='background-color: ".$row['colour']."; color:white;'>".$row['tag']."</span></th></tr>
								
								if(count($tags) == 1 && $tags[0] == ""){
									echo "<th></th></tr>";
								}
								else{
									echo "<th>";
									for($j = 0; $j < count($tags); $j++){
										if($tags[$j] != null){
											$tmpTagExplode = explode(',', $tags[$j]);
											echo "<span class='Test' style='background-color: ".$tmpTagExplode[2].";'>".$tmpTagExplode[1]."</span> ";
										}
									}
									echo "</th></tr>";
								}
							}
							else{
								echo "<tr><th style='padding-right:20px;'><form action='../Backend/recordUpdate.php' method='post' style='display:inline; margin-right: 20px;' onclick='return confirm(&quot;Are you sure you want to delete this entry?&quot;);'><input name='recordID' value='".$recordArray[$i][0]."' hidden></input><input class='RecordEditDeleteButton' type='submit' name='recordDeleteButton' value='×' style='color: #FF3650;'></form><button class='RecordEditDeleteButton' onclick='UpdateEditSettings(".$recordArray[$i][0].");'>+</button></th><th>" .date_format($date, "d/m/y"). "</th><th style='color: #FF3650;'>-$".str_replace("-", "", number_format($recordArray[$i][2],2))."</th><th>".$recordArray[$i][3]."</th><th style='max-width: 200px;'>".$recordArray[$i][4]."</th>";										
								
								if(count($tags) == 1 && $tags[0] == ""){
									echo "<th></th></tr>";
								}
								else{
									echo "<th>";
									for($j = 0; $j < count($tags); $j++){
										if($tags[$j] != null){
											$tmpTagExplode = explode(',', $tags[$j]);
											echo "<span class='Test' style='background-color: ".$tmpTagExplode[2].";'>".$tmpTagExplode[1]."</span> ";
										}
									}
									echo "</th></tr>";
								}
							}
						}
					?>
					</table>
				</div>
			</div>
			<div class="PageBodySidebar" id="sideBarWithTab">
				<div class="PageBodyFullCell" id="tabSelect">
					<button id="addRecordTab" onclick="SwitchToFilter(false);" class="RecordEditDeleteButton" style="padding: 10px; border: 1px solid #858585; border:none;">ADD / EDIT</button>
					<button id="filterRecordTab" onclick="SwitchToFilter(true);" class="RecordEditDeleteButton" style="padding: 10px; border: 1px solid #858585; border:none;">FILTER</button>
				</div>
				<div id="addRecord" style="padding: 20px; background-color: #4a4a4a; height: 757px;">
					<h1 id="AddEditRecordTitle">ADD RECORD</h1>
					<form action="../Backend/recordUpdate.php" method="post">
						<table class="HomePageRunDown">
							<tr>
								<th>
									<h2>DATE</h2>
								</th>
								<th>
									<input type="date" name="recordDate" id="recordDate">
									<input name="recordID" id="recordID" value="" hidden></input>
								</th>
							</tr>	
							<tr>
								<th>
									<h2>AMOUNT</h2>
								</th>
								<th>
									<input type="number" name="recordAmount" id="recordAmount" value="" step="0.01">
								</th>
							</tr>
							<tr>
								<th>
									<h2>ACCOUNT</h2>
								</th>
								<th>
									<select class="DropDown" name="recordAccount" id="recordAccount">
										<option value='0'>DANIEL</option>
										<option value='1'>NATHAN</option>
										<option value='2'>BOTH</option>
									</select>
								</th>
							</tr>
							<tr>
								<th>
									<h2>INFO</h2>
								</th>
								<th>
									<input type="text" name="recordInfo" id="recordInfo" value="">
								</th>
							</tr>
							<tr>
								<th>
									<h2>TAGS</h2>
								</th>
								<th>
									<div class="ScrollableList" style="height: 150px;" id="tagScrollableList">
										<?php
											$tagArray = array();
											GetSQLRowToArray("tags", $tagArray, "tagID;tagName;tagColour;tagDescription");
											for($i = 0; $i < count($tagArray); $i++){
												echo "<input type='checkbox' name='tagCB".$tagArray[$i][0]."' id='tagCB".$tagArray[$i][0]."' value='1'><span class='Test' style='background-color: ".$tagArray[$i][2]."'>".$tagArray[$i][1]."</span></input><br>";
											}
										?>
									</div>
								</th>
							</tr>
							<tr>
								<th>
								</th>
								<th>
									<button class='NewTagButton' type='button' onclick='UpdateEditSettings(0)' style='color: #FF3650;'>RESET FIELDS</button>
								</th>
							</tr>							
							<tr>
								<th>
									<h2></h2>
								</th>
								<th>
									<input type="submit" name="recordSubmitButton" id="recordSubmitButton" value="CREATE RECORD">
								</th>
							</tr>
						</table>
					</form>
				</div>
				<div id="filterRecord" style="display:none; padding: 20px; background-color: #4a4a4a; height: 757px;">
					<h1 style='color: yellow;'>NOT WORKING</h1>
					<table class="HomePageRunDown">
						<tr>
							<th>
								<h2>DATE FROM</h2>
							</th>
							<th>
								<input type="date" name="tagName" id="tagName" value="<?php echo date('Y-m-d');?>" style="height: 30px;">
							</th>
						</tr>
						<tr>
							<th>
								<h2>DATE TO</h2>
							</th>
							<th>
								<input type="date" name="tagName" id="tagName" value="<?php echo date('Y-m-d');?>" style="height: 30px;">
							</th>
						</tr>							
						<tr>
							<th>
								<h2>AMOUNT FROM</h2>
							</th>
							<th>
								<input type="number" name="tagName" id="tagName" value="" style="height: 30px;">
							</th>
						</tr>
						<tr>
							<th>
								<h2>AMOUNT TO</h2>
							</th>
							<th>
								<input type="number" name="tagName" id="tagName" value="" style="height: 30px;">
							</th>
						</tr>
						<tr>
							<th>
								<h2>ACCOUNT</h2>
							</th>
							<th>
								<select class="DropDown" name="tagSelect" id="tagSelect">
									<option value='0'>DANIEL</option>
									<option value='1'>NATHAN</option>
								</select>
							</th>
						</tr>
						<tr>
							<th>
								<h2>TAGS</h2>
							</th>
							<th>
								<div class="ScrollableList" style="height: 150px;">
									<?php
										$tagArray = array();
										GetSQLRowToArray("tags", $tagArray, "tagID;tagName;tagColour;tagDescription");
										for($i = 0; $i < count($tagArray); $i++){
											echo "<input type='checkbox' name='tag".$tagArray[$i][0]."' value='1'><span class='Test' style='background-color: ".$tagArray[$i][2]."'>".$tagArray[$i][1]."</span></input><br>";
										}
									?>
								</div>
							</th>
						</tr>						
						<tr>
							<th>
								<h2></h2>
							</th>
							<th>
								<input type="submit" name="tagSubmitButton" id="tagSubmitButton" value="UPDATE FILTER">
							</th>
						</tr>
					</table>
				</div>
			</div>
			<div style="margin: 10px;"></div>
		</div>
	</body>
</html>
<?php ob_end_flush(); ?>