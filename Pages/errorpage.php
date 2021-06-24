<html>
	<head>
		<title>FINANCE WEBSITE V1</title>
		<link rel="stylesheet" href="../Stylesheet/Stylesheet.css">
		<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@200;600;700&display=swap" rel="stylesheet">
	</head>
	<body>
		<div class="PageBodyWrapper" style="margin-top: 10%; margin-left: 30%; margin-right: 30%; width: 40%; height: auto;">
			<div style="width: 100%; text-align: center; padding: 30px 0px 30px 0px; border: 1px solid #B6F76B;">
				<h1>
					<?php
						/*
							ERROR MESSAGES
							
							00 - DB CONNECTION ERROR
							01 - SQL SYNTAX ERROR IN FireSQLQuery
							02 - SQL SYNTAX ERROR IN FireSQLQueryGetInsertID
							03 - SQL SYNTAX ERROR IN GetSQLRowToArray
							04 - SQL SYNTAX ERROR IN GetSQLRowToArrayWhere
							05 - SQL SYNTAX ERROR IN GetFinancesWithTags
							06 - SQL SYNTAX ERROR IN GetAccountSum
							07 - SQL SYNTAX ERROR IN GetAccountSumByGroupToArray
						*/
					
						switch($_SERVER['QUERY_STRING']){
							//CUSTOM ERROR MESSAGES
							case "00":
								echo "ERROR: 0.0";
								break;
							case "01":
								echo "ERROR: 0.1";
								break;
							case "02":
								echo "ERROR: 0.2";
								break;
							case "03":
								echo "ERROR: 0.3";
								break;
							case "04":
								echo "ERROR: 0.4";
								break;
							case "05":
								echo "ERROR: 0.5";
								break;
							case "06":
								echo "ERROR: 0.6";
								break;
							case "07":
								echo "ERROR: 0.7";
								break;
							
							//DEFAULT ERROR MESSAGES
							case "400":
								echo "ERROR: 400";
								break;
							case "401":
								echo "ERROR: 401";
								break;
							case "403":
								echo "ERROR: 403";
								break;
							case "404":
								echo "ERROR: 404";
								break;
							case "500":
								echo "ERROR: 500";
								break;
							default:
								echo "ERROR: UNKNOWN";
								break;
						}
					?>
				</h1>
				<p>
					<?php
						switch($_SERVER['QUERY_STRING']){
							//CUSTOM ERROR MESSAGSE
							case "00":
								echo "Failed to Connect to Database:<br>- Check Database Connection Function Username and Password";
								break;
							case "01":
								echo "SQL QUERY FAILED TO FIRE IN phpFunctions.FireSQLQuery()";
								break;
							case "02":
								echo "SQL QUERY FAILED TO FIRE IN phpFunctions.FireSQLQueryGetInsertID()";
								break;
							case "03":
								echo "SQL QUERY FAILED TO FIRE IN phpFunctions.GetSQLRowToArray()";
								break;
							case "04":
								echo "SQL QUERY FAILED TO FIRE IN phpFunctions.GetSQLRowToArrayWhere()";
								break;
							case "05":
								echo "SQL QUERY FAILED TO FIRE IN phpFunctions.GetFinancesWithTags()";
								break;
							case "06":
								echo "SQL QUERY FAILED TO FIRE IN phpFunctions.GetAccountSum()";
								break;
							case "07":
								echo "SQL QUERY FAILED TO FIRE IN phpFunctions.GetAccountSumByGroupToArray()";
								break;
							
							//DEFAULT ERROR MESSAGES
							case "400":
								echo "BAD REQUEST";
								break;
							case "401":
								echo "UNAUTHORIZED ACCESS";
								break;
							case "403":
								echo "FORBIDDEN";
								break;
							case "404":
								echo "PAGE NOT FOUND";
								break;
							case "500":
								echo "SERVER ERROR";
								break;
							default:
								echo "Unknown Error";
								break;
						}
					?>
				</p>
				<a href="../index.php" style="font-weight: 700;">[BACK TO HOMEPAGE]</a>
			</div>
		</div>
	</body>
</html>