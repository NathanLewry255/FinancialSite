<?php include "../Backend/phpFunctions.php"; 

if(isset($_POST['tagDeleteButton'])){
	DeleteTag($_POST['tagID']);
	header("Location: ../Pages/tags.php");
	exit();
}
else if($_POST['tagSubmitButton'] == "CREATE TAG"){
	if($_POST['tagName'] == ""){
		header("Location: ../Pages/tags.php?NullName");
		exit();
	}
	if($_POST['tagColour'] == ""){
		header("Location: ../Pages/tags.php?NullColour");
		exit();
	}
	CreateTag(SanitizeInput($_POST['tagName']), $_POST['tagColour'], SanitizeInput($_POST['tagDescription']));
	header("Location: ../Pages/tags.php");
	exit();
}
else if($_POST['tagSubmitButton'] == "UPDATE TAG"){
	if($_POST['tagName'] == ""){
		header("Location: ../Pages/tags.php?NullName");
		exit();
	}
	if($_POST['tagColour'] == ""){
		header("Location: ../Pages/tags.php?NullColour");
		exit();
	}
	UpdateTag($_POST['tagID'], SanitizeInput($_POST['tagName']), $_POST['tagColour'], SanitizeInput($_POST['tagDescription']));
	header("Location: ../Pages/tags.php");
	exit();
}


function CreateTag($tagName, $tagColour, $tagDescription){
	$dbConnection = GetSQLConnection();
	$queryResult = FireSQLQuery($dbConnection, "INSERT INTO tags(tagName, tagColour, tagDescription) VALUES ('".strtoupper($tagName)."', '".$tagColour."', '".$tagDescription."')");
}

function UpdateTag($tagID, $tagName, $tagColour, $tagDescription){
	$dbConnection = GetSQLConnection();
	$queryResult = FireSQLQuery($dbConnection, "UPDATE tags SET tagName = '".strtoupper($tagName)."', tagColour = '".$tagColour."', tagDescription = '".$tagDescription."' WHERE tagID = ".$tagID."");
}

function DeleteTag($tagID){
	$dbConnection = GetSQLConnection();
	$queryResult = FireSQLQuery($dbConnection, "DELETE FROM tags WHERE tagID = ".$tagID."");
	CleanRecordTags($tagID);
}

function CleanRecordTags($tagID){
	$dbConnection = GetSQLConnection();
	$queryResult = FireSQLQuery($dbConnection, "DELETE FROM tagfinancelink WHERE tagID = ".$tagID."");
}
	
?>