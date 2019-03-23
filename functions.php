<?php
$random_salt_length = 32;
/**
* Queries the database and checks whether the user already exists
* 
* @param $username
* 
* @return
*/
function userExists($username){
	$query = "SELECT username FROM member WHERE username = ?";
	global $con;
	if($stmt = $con->prepare($query)){
		$stmt->bind_param("s",$username);
		$stmt->execute();
		$stmt->store_result();
		$stmt->fetch();
		if($stmt->num_rows == 1){
			$stmt->close();
			return true;
		}
		$stmt->close();
	}
 
	return false;
}
 
/**
* Creates a unique Salt for hashing the password
* 
* @return
*/
function getSalt(){
	global $random_salt_length;
	return bin2hex(openssl_random_pseudo_bytes($random_salt_length));
}
 
/**
* Creates password hash using the Salt and the password
* 
* @param $password
* @param $salt
* 
* @return
*/
function concatPasswordWithSalt($password,$salt){
	global $random_salt_length;
	if($random_salt_length % 2 == 0){
		$mid = $random_salt_length / 2;
	}
	else{
		$mid = ($random_salt_length - 1) / 2;
	}
 
	return
	substr($salt,0,$mid - 1).$password.substr($salt,$mid,$random_salt_length - 1);
 
}
?>