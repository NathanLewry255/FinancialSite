<?php include "../Backend/phpFunctions.php"; 

if(isset($_POST['recordDeleteButton'])){
	DeleteRecord($_POST['recordID']);
	header("Location: ../Pages/finances.php");
	exit();
}

else if($_POST['recordSubmitButton'] == "CREATE RECORD"){
	
	$selectedTagsArray = array();
	GetSelectedTags($selectedTagsArray);
	
	if($_POST['recordDate'] == ""){
		header("Location: ../Pages/finances.php?NullDate");
		exit();
	}
	if($_POST['recordAmount'] == ""){
		header("Location: ../Pages/finances.php?NullAmount");
		exit();
	}
	if($_POST['recordAccount'] == 0){
		$recordAccount = "Daniel";
	}
	else if($_POST['recordAccount'] == 1){
		$recordAccount = "Nathan";
	}
	else if($_POST['recordAccount'] == 2){
		$recordAccount = "Nathan";
		$recordID = CreateRecord(date("Y-m-d", strtotime($_POST['recordDate'])), $_POST['recordAmount'], $recordAccount, SanitizeInput($_POST['recordInfo']));
		AddSelectedTags($recordID, $selectedTagsArray);
		$recordAccount = "Daniel";
		$recordID = CreateRecord(date("Y-m-d", strtotime($_POST['recordDate'])), $_POST['recordAmount'], $recordAccount, SanitizeInput($_POST['recordInfo']));
		AddSelectedTags($recordID, $selectedTagsArray);
		header("Location: ../Pages/finances.php");
		exit();
	}
	else{
		header("Location: ../Pages/finances.php?NullAccount");
		exit();
	}
	
	$recordID = CreateRecord(date("Y-m-d", strtotime($_POST['recordDate'])), $_POST['recordAmount'], $recordAccount, SanitizeInput($_POST['recordInfo']));
	AddSelectedTags($recordID, $selectedTagsArray);
	header("Location: ../Pages/finances.php");
	exit();
}

else if($_POST['recordSubmitButton'] == "UPDATE RECORD"){
	echo "UPDATE";
	$selectedTagsArray = array();
	GetSelectedTags($selectedTagsArray);
	
	if($_POST['recordDate'] == ""){
		header("Location: ../Pages/finances.php?NullDate");
		exit();
	}
	if($_POST['recordAmount'] == ""){
		header("Location: ../Pages/finances.php?NullAmount");
		exit();
	}
	if($_POST['recordAccount'] == 0){
		$recordAccount = "Daniel";
	}
	else if($_POST['recordAccount'] == 1){
		$recordAccount = "Nathan";
	}
	else if($_POST['recordAccount'] == 2){
		$recordAccount = "Nathan";
		UpdateRecord($_POST['recordID'], date("Y-m-d", strtotime($_POST['recordDate'])), $_POST['recordAmount'], $recordAccount, SanitizeInput($_POST['recordInfo']));
		RemoveUnselectedTags($_POST['recordID'], $selectedTagsArray);
		AddSelectedTags($_POST['recordID'], $selectedTagsArray);
		$recordAccount = "Daniel";
		$recordID = CreateRecord(date("Y-m-d", strtotime($_POST['recordDate'])), $_POST['recordAmount'], $recordAccount, SanitizeInput($_POST['recordInfo']));
		AddSelectedTags($recordID, $selectedTagsArray);
		header("Location: ../Pages/finances.php");
		exit();
	}
	else{
		header("Location: ../Pages/finances.php?NullAccount");
		exit();
	}

	UpdateRecord($_POST['recordID'], date("Y-m-d", strtotime($_POST['recordDate'])), $_POST['recordAmount'], $recordAccount, SanitizeInput($_POST['recordInfo']));
	RemoveUnselectedTags($_POST['recordID'], $selectedTagsArray);
	AddSelectedTags($_POST['recordID'], $selectedTagsArray);
	header("Location: ../Pages/finances.php");
	exit();
}


function GetSelectedTags(&$selectedTagsArray){
	$tagArray = array();
	GetSQLRowToArray("tags", $tagArray, "tagID");
	for($i = 0; $i < count($tagArray); $i++){
		if(isset($_POST["tagCB" . $tagArray[$i][0]])){
			array_push($selectedTagsArray, $tagArray[$i][0]);
		}
	}
}

function RemoveUnselectedTags($recordID, $selectedTags){
	$tagsInDB = array();
	GetSQLRowToArrayWhere("tagfinancelink", $tagsInDB, "id;tagIDFK", "recordIDFK = ".$recordID."");
	for($i = 0; $i < count($tagsInDB); $i++){
		$isStillSelected = false;
		for($j = 0; $j < count($selectedTags); $j++){
			if($tagsInDB[$i][1] == $selectedTags[$j]){
				$isStillSelected = true;
				continue;
			}
		}
		if(!$isStillSelected){
			$dbConnection = GetSQLConnection();
			$queryResult = FireSQLQuery($dbConnection, "DELETE FROM tagfinancelink WHERE id = ".$tagsInDB[$i][0]."");
		}
	}
}

function AddSelectedTags($recordID, $selectedTags){
	$tagsInDB = array();
	GetSQLRowToArrayWhere("tagfinancelink", $tagsInDB, "tagIDFK", "recordIDFK = ".$recordID."");
	for($i = 0; $i < count($selectedTags); $i++){
		$isInDB = false;
		for($j = 0; $j < count($tagsInDB); $j++){
			if($tagsInDB[$j][0] == $selectedTags[$i]){
				$isInDB = true;
				continue;
			}
		}
		if(!$isInDB){
			$dbConnection = GetSQLConnection();
			$queryResult = FireSQLQuery($dbConnection, "INSERT INTO tagfinancelink (recordIDFK, tagIDFK) VALUES (".$recordID.", ".$selectedTags[$i].")");
		}
	}
}

function CreateRecord($recordDate, $recordAmount, $recordAccount, $recordInfo){
	$dbConnection = GetSQLConnection();
	$queryResult = FireSQLQueryGetInsertID($dbConnection, "INSERT INTO finances(recordAmount, recordAccount, recordInfo, recordDate) VALUES (".$recordAmount.", '".$recordAccount."', '".$recordInfo."', '".$recordDate."')");
	return $queryResult;
}

function UpdateRecord($recordID, $recordDate, $recordAmount, $recordAccount, $recordInfo){
	$dbConnection = GetSQLConnection();
	$queryResult = FireSQLQuery($dbConnection, "UPDATE finances SET recordAmount = ".$recordAmount.", recordAccount = '".$recordAccount."', recordInfo = '".$recordInfo."', recordDate = '".$recordDate."' WHERE recordID = ".$recordID."");
}

function DeleteRecord($recordID){
	$dbConnection = GetSQLConnection();
	$queryResult = FireSQLQuery($dbConnection, "DELETE FROM finances WHERE recordID = ".$recordID."");
	CleanRecordTags($recordID);
}

function CleanRecordTags($recordID){
	$dbConnection = GetSQLConnection();
	$queryResult = FireSQLQuery($dbConnection, "DELETE FROM tagfinancelink WHERE recordIDFK = ".$recordID."");
}
	
?>