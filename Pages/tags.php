<?php ob_start(); session_start(); include "../Backend/phpFunctions.php"; if(!isset($_SESSION['loggedIn'])){header("Location: ../Pages/login.php");}?>
<html>
	<head>
		<title>FINANCE WEBSITE V1</title>
		<link rel="stylesheet" href="../Stylesheet/Stylesheet.css">
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@200;600;700&display=swap" rel="stylesheet">
	</head>
	<script>
		var tagsArray = [];
		function UpdateEditSettings(tagID){
			if(tagID == 0){
				document.getElementById("formTagID").value = 0;
				document.getElementById("tagName").value = "";
				document.getElementById("tagColour").value = "#000000";
				document.getElementById("tagDescription").value = "";
				document.getElementById("tagSubmitButton").value = "CREATE TAG";
				return;
			}
			for(var i = 0; i < tagsArray.length; i++){
				if(tagsArray[i][0] == tagID){
					document.getElementById("formTagID").value = tagsArray[i][0];
					document.getElementById("tagName").value = tagsArray[i][1];
					document.getElementById("tagColour").value = tagsArray[i][2];
					document.getElementById("tagDescription").value = tagsArray[i][3];
					document.getElementById("tagSubmitButton").value = "UPDATE TAG";
					return;
				}
			}
		}
		
		function PushTagToArray(tagID, tagName, tagColour, tagDescription){
			//console.log(tagID + tagName + tagColour + tagDescription);
			var tmp = [tagID, tagName, tagColour, tagDescription];
			tagsArray.push(tmp);
		}
	</script>
	<body>
		<div class="NavBar">
			<span id="Title"><a href="../index.php">FINANCIAL SITE</a></span>
			<span id="NavLink"><a href="finances.php">FINANCES</a></span>
			<span id="NavLink"><a href="tags.php">TAGS</a></span>
		</div>
		<div class="PageBodyWrapper">
			<div class="PageBodySidebarBody">
				<h1>TAGS</h1>
				<div class="ScrollableList">
					<table class="TagsPageTable">
					<tr><th style='padding-right:20px;'></th><th style="color: #B6F76B;"><h2>TAG</h2></th><th style="color: #B6F76B;"><h2>DESCRIPTION</h2></th></tr>
					<?php
						$tagArray = array();
						GetSQLRowToArray("tags", $tagArray, "tagID;tagName;tagColour;tagDescription");
						for($i = 0; $i < count($tagArray); $i++){
							/*list($r, $g, $b) = sscanf($tagArray[$i][2], "#%02x%02x%02x");
							if($r + $g + $b < 200){
								echo "<tr><th><span class='Test' style='background-color: ".$tagArray[$i][2]."; color:white;'>".$tagArray[$i][1]."</span></th><th>".$tagArray[$i][3]."</th></tr>";
							}*/
							//else{
								echo "<tr><th style='padding-right:20px;'><form action='../Backend/tagUpdate.php' method='post' style='display:inline; margin-right: 20px;' onclick='return confirm(&quot;Are you sure you want to delete this tag?&quot;);'><input name='tagID' value='".$tagArray[$i][0]."' hidden></input><input class='RecordEditDeleteButton' type='submit' name='tagDeleteButton' value='×' style='color: #FF3650;'></form><button class='RecordEditDeleteButton' onclick='UpdateEditSettings(".$tagArray[$i][0].")'>+</button></th><th><span class='Test' style='background-color: ".$tagArray[$i][2]."'>".$tagArray[$i][1]."</span></th><th>".$tagArray[$i][3]."</th></tr>";
							//}
							echo "<script>PushTagToArray(".$tagArray[$i][0].",'".$tagArray[$i][1]."','".$tagArray[$i][2]."','".$tagArray[$i][3]."');</script>";
						}
					?>
					</table>
				</div>
			</div>
			<div class="PageBodySidebar">
				<h1>ADD / EDIT TAG</h1>
				<form action="../Backend/tagUpdate.php" method="post">
					<input id="formTagID" name="tagID" value=0 hidden></input>
					<table class="HomePageRunDown">	
						<tr>
							<th>
								<h2>NAME</h2>
							</th>
							<th>
								<input type="text" name="tagName" id="tagName" value="">
							</th>
						</tr>	
						<tr>
							<th>
								<h2>COLOUR</h2>
							</th>
							<th>
								<input type="color" name="tagColour" id="tagColour" value="#000000">
							</th>
						</tr>
						<tr>
							<th>
								<h2>INFO</h2>
							</th>
							<th>
								<input type="text" name="tagDescription" id="tagDescription" value="">
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
								<input type="submit" name="tagSubmitButton" id="tagSubmitButton" value="CREATE TAG">
							</th>
						</tr>
					</table>
				</form>
			</div>
			<div style="margin: 10px;"></div>
		</div>
	</body>
</html>
<?php ob_end_flush(); ?>