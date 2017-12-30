<?php
//Simple exchange IP service

//Setup / Настройка
$SaltLogins = "1234"; 
$SaltKeys = "5678";
$RootPassword = "9012";
$LoginsFolderName = "logins";
$LoginsFilesFormat = "php";

function GetIp() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
	else{
		$ip = $_SERVER['REMOTE_ADDR'];
		}
	return $ip;
}

$SetLoginData = trim(substr(htmlspecialchars($_GET['set']), 0, 22));
$GetLoginData = trim(substr(htmlspecialchars($_GET['get']), 0, 22));
$RemoveLogin = trim(substr(htmlspecialchars($_GET['rm']), 0, 22));
$Key = trim(substr(htmlspecialchars($_GET['key']), 0, 32));

if ($SetLoginData != "") {
	$Filename = $LoginsFolderName . "/" . md5($SetLoginData . $SaltLogins) . "." . $LoginsFilesFormat;

	if (!file_exists($Filename)) {
		$f = fopen($Filename, "w");
		fwrite($f, md5($SetLoginData . $SaltKeys) . " " . GetIp()); 
		fclose($f);
		echo md5($SetLoginData . $SaltKeys);
	} else {
		if ($Key != "") {
			$f = fopen($Filename, "r");
			$buff = fread($f, filesize($Filename));
			$buff = explode(" ", $buff);
			fclose($f);
			if ($buff[0] == $Key) {
				$f = fopen($Filename, "w");
				fwrite($f, md5($SetLoginData . $SaltKeys) . " " . GetIp()); 
				fclose($f);
				echo "Updated";
			} else {
				echo "Invalid key";
			}
		} else {
			echo "Invalid key";
		}
	}
}

if ($GetLoginData != "") {
		$Filename = $LoginsFolderName . "/" . md5($GetLoginData . $SaltLogins) . "." . $LoginsFilesFormat;
		if (file_exists($Filename)) {
			$f = fopen($Filename, "r");
			$buff = fread($f, filesize($Filename));
			$buff = explode(" ", $buff);
			fclose($f);
			echo $buff[1];
		} else {
			echo "Invalid login";
		}
}

if ($RemoveLogin != "") {
		if ($Key == $RootPassword) {
		$Filename = $LoginsFolderName . "/" . md5($RemoveLogin . $SaltLogins) . "." . $LoginsFilesFormat;
		if (file_exists($Filename)) {
			unlink($_SERVER['DOCUMENT_ROOT'] . "/" . $Filename);
			echo "Login deleted";
		} else {
			echo "Invalid login";
		}
	} else {
		echo "Invalid key";
	}
}

if ($SetLoginData == "" and $GetLoginData ==  "" and $RemoveLogin == "" and $Key == "")
	echo "Exchange IP service";
?>