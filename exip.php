<?php
/* Simple exchange IP service 
En: Create / Ru: Создать IР https://example.com/exchange.php?method=create&user=testuser
En: Update / Ru: Обновить IР https://example.com/exchange.php?method=set&user=testuser&key=<USER_KEY>
En: Get IP / Ru: Получить IP Р https://example.com/exchange.php?method=get&user=testuser
En: Remove / Ru: Удалить IР https://example.com/exchange.php?method=rm&user=testuser&key=9012
*/

$UsersDir = 'users';
$RootPassword = 'T1Mc7sA9m4A3';
$Salt = 'my_secret_salt';
$DataExt = '.php';

if (!is_dir($UsersDir))
	mkdir($UsersDir, 0755, true);

// En: Only a-z A-Z 0-9 _ -)
function SafeUser($username) {
	return substr(preg_replace('/[^a-zA-Z0-9_\-]/', '', $username), 0, 22);
}

// En: Get real client IP address / Ru: Получить реальный IP клиента
function GetIP() {
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
		return $_SERVER['HTTP_CLIENT_IP'];
	else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	else
		return $_SERVER['REMOTE_ADDR'];
}

$method = isset($_GET['method']) ? $_GET['method'] : '';
$user = isset($_GET['user']) ? SafeUser($_GET['user']) : '';
$key = isset($_GET['key']) ? $_GET['key'] : '';
$file = $UsersDir . '/' . $user . $DataExt;

switch ($method) {
	case 'create':
		if ($user == '') exit('invalid user');
		if (file_exists($file)) exit('user already exists');
		$new_key = md5($user . $Salt);
		file_put_contents($file,  $new_key . ' 0.0.0.0', LOCK_EX);

		echo $new_key;
	break;

	case 'set':
		if ($user == '' || $key == '') exit('invalid request');
		if (!file_exists($file)) exit('user not exists');

		list($stored_key,) = explode(' ', file_get_contents($file));
		if ($stored_key != $key) exit('invalid key');
		file_put_contents($file, $stored_key . ' ' . GetIP(), LOCK_EX);
		
		echo 'updated';
	break;


	case 'get':
		if ($user == '' || !file_exists($file)) {
			echo '0.0.0.0';
			break;
		}
		list(, $ip) = explode(' ', file_get_contents($file));
		echo $ip;
	break;

	case 'rm':
		if ($key != $RootPassword) exit('invalid key');
		if ($user == '' || !file_exists($file)) exit('user not exists');

		unlink($file);
		echo 'removed';
	break;


	default:
		echo 'Exchange IP service';
}
?>
