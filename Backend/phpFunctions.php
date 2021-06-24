<?php
date_default_timezone_set('Pacific/Auckland');

/*
	ERROR MESSAGES
	
	phpFunctions.php
	00 - DB CONNECTION ERROR
	01 - SQL SYNTAX ERROR IN FireSQLQuery()
	02 - SQL SYNTAX ERROR IN FireSQLQueryGetInsertID()
	03 - SQL SYNTAX ERROR IN GetSQLRowToArray()
	04 - SQL SYNTAX ERROR IN GetSQLRowToArrayWhere()
	05 - SQL SYNTAX ERROR IN GetFinancesWithTags()
	06 - SQL SYNTAX ERROR IN GetAccountSum()
	07 - SQL SYNTAX ERROR IN GetAccountSumByGroupToArray()

*/

function ConnectToDB($servername, $username, $password, $database){
	$dbConnection = new mysqli($servername, $username, $password, $database);

	if($dbConnection -> connect_errno){
		ThrowError("00");
	}
	return $dbConnection;
}

function GetSQLConnection(){
	return ConnectToDB("localhost", "root", "", "financewebsite"); //[IMPORTANT] SHOULD BE ("localhost", "root2", "root2", "financewebsite") FOR LIVE SITE
}

function ThrowError($errorMessage){
	$folder = "FinanceSite";	//[IMPORTANT] SHOULD BE "" FOR LIVE SITE
	if($folder == ""){
		header("Location: http://".$_SERVER['HTTP_HOST']."/Pages/errorpage.php?".$errorMessage);
		exit();
	}
	else{
		header("Location: http://".$_SERVER['HTTP_HOST']."/".$folder."/Pages/errorpage.php?".$errorMessage);
		exit();
	}
}

function FireSQLQuery($dbConnection, $query){
	$result = $dbConnection->query($query);
	if (!$result) {
		ThrowError("01");
	} else {
		$dbConnection->close();
		return $result;
	}
}

function FireSQLQueryGetInsertID($dbConnection, $query){
	$result = $dbConnection->query($query);
	if (!$result) {
		ThrowError("02");
	} else {
		$insertID = $dbConnection->insert_id;
		$dbConnection->close();
		return $insertID;
	}
}

function GetSQLRowToArray($table, &$array, $columns){
	$columns = explode(";", $columns);
	$dbConnection = GetSQLConnection();
	$queryResult = FireSQLQuery($dbConnection, "SELECT * FROM ".$table."");
	if($queryResult != null){
		if(mysqli_num_rows($queryResult)==0){
			return;
		}
		while($row = $queryResult->fetch_assoc()) {
			$tmp = array();
			for($i = 0; $i < count($columns); $i++){
				array_push($tmp, $row[$columns[$i]]);
			}
			array_push($array, $tmp);
		}
	}
	else{
		ThrowError("03");
	}
}

function GetSQLRowToArrayWhere($table, &$array, $columns, $whereQuery){
	$columns = explode(";", $columns);
	$dbConnection = GetSQLConnection();
	$queryResult = FireSQLQuery($dbConnection, "SELECT * FROM ".$table." WHERE ".$whereQuery."");
	if(mysqli_num_rows($queryResult)==0){
		return;
	}
	while($row = $queryResult->fetch_assoc()) {
		$tmp = array();
		for($i = 0; $i < count($columns); $i++){
			array_push($tmp, $row[$columns[$i]]);
		}
		array_push($array, $tmp);
	}
}

function GetFinancesWithTags(&$array, $columns){
	$columns = explode(";", $columns);
	$dbConnection = GetSQLConnection();
	$queryResult = FireSQLQuery($dbConnection, "SELECT * FROM finances LEFT JOIN tagfinancelink ON tagfinancelink.recordIDFK = finances.recordID LEFT JOIN tags ON tags.tagID = tagfinancelink.tagIDFK ORDER BY finances.recordDate DESC");
	if(mysqli_num_rows($queryResult)==0){
		return;
	}
	$currentRecordID;
	$lastRecordID;
	$tagsStr = '';
	$tmpRecordArray = array();
	
	while($row = $queryResult->fetch_assoc()) {
		$currentRecordID = $row["recordID"];
		
		if(isset($lastRecordID) && $currentRecordID != $lastRecordID){
			$tagsStr = substr($tagsStr, 0, -1);
			array_push($tmpRecordArray, $tagsStr);
			array_push($array, $tmpRecordArray);
			unset($tmpRecordArray);
			$tmpRecordArray = array();
			$tagsStr = '';
		}
		
		if(!isset($lastRecordID) || $currentRecordID != $lastRecordID){
			$lastRecordID = $currentRecordID;
			for($i = 0; $i < count($columns)-3; $i++){
				array_push($tmpRecordArray, $row[$columns[$i]]);
			}
		}
		
		if($row["tagID"] != ""){
			$tagsStr .= $row["tagID"].",".$row["tagName"].",".$row["tagColour"].";";
		}
	}
	$tagsStr = substr($tagsStr, 0, -1);
	array_push($tmpRecordArray, $tagsStr);
	array_push($array, $tmpRecordArray);
}

function GetAccountSum($accountName, $condition){
	$dbConnection = GetSQLConnection();
	if($condition == ""){
		$row = FireSQLQuery($dbConnection, "SELECT SUM(recordAmount) AS sum FROM finances WHERE recordAccount = '".$accountName."'")->fetch_assoc();
	}
	else{
		$row = FireSQLQuery($dbConnection, "SELECT SUM(recordAmount) AS sum FROM finances WHERE recordAccount = '".$accountName."' AND ".$condition."")->fetch_assoc();
	}
	if($row != null){
		return $row['sum'];
	}
	else{
		ThrowError("06");
	}
}

function GetAccountSumByGroupToArray($accountName, $condition, $group, $columns, &$array, $week){
	$columns = explode(";", $columns);
	$dbConnection = GetSQLConnection();
	if($condition == ""){
		$queryResult = FireSQLQuery($dbConnection, "SELECT SUM(CASE WHEN recordAmount > 0 THEN recordAmount ELSE 0 END) AS incomeSum, SUM(CASE WHEN recordAmount < 0 THEN recordAmount ELSE 0 END) as expendSum, recordDate FROM finances WHERE recordAccount = '".$accountName."' GROUP BY ".$group." ORDER BY recordDate ASC");
	}
	else{
		$queryResult = FireSQLQuery($dbConnection, "SELECT SUM(CASE WHEN recordAmount > 0 THEN recordAmount ELSE 0 END) AS incomeSum, SUM(CASE WHEN recordAmount < 0 THEN recordAmount ELSE 0 END) as expendSum, recordDate FROM finances WHERE recordAccount = '".$accountName."' AND ".$condition." GROUP BY ".$group." ORDER BY recordDate ASC");
	}
	while($row = $queryResult->fetch_assoc()) {
		$tmp = array();
		for($i = 0; $i < count($columns); $i++){
			array_push($tmp, $row[$columns[$i]]);
		}
		array_push($array, $tmp);
	}
}

function GetAccountSumByGroupToArrayYear($accountName, $condition, $group, $columns, &$array){
	$columns = explode(";", $columns);
	$dbConnection = GetSQLConnection();
	if($condition == ""){
		$queryResult = FireSQLQuery($dbConnection, "SELECT SUM(CASE WHEN recordAmount > 0 THEN recordAmount ELSE 0 END) AS incomeSum, SUM(CASE WHEN recordAmount < 0 THEN recordAmount ELSE 0 END) as expendSum, DATE_FORMAT(recordDate, '%Y-%m') AS recordDate FROM finances WHERE recordAccount = '".$accountName."' GROUP BY ".$group." ORDER BY recordDate ASC");
	}
	else{
		$queryResult = FireSQLQuery($dbConnection, "SELECT SUM(CASE WHEN recordAmount > 0 THEN recordAmount ELSE 0 END) AS incomeSum, SUM(CASE WHEN recordAmount < 0 THEN recordAmount ELSE 0 END) as expendSum, DATE_FORMAT(recordDate, '%Y-%m') AS recordDate FROM finances WHERE recordAccount = '".$accountName."' AND ".$condition." GROUP BY ".$group." ORDER BY recordDate ASC");
	}
	while($row = $queryResult->fetch_assoc()) {
		$tmp = array();
		for($i = 0; $i < count($columns); $i++){
			array_push($tmp, $row[$columns[$i]]);
		}
		array_push($array, $tmp);
	}
}

function SanitizeInput($input){
	$sanitizedInput = htmlspecialchars($input, ENT_QUOTES);
	return $sanitizedInput;
}
?>