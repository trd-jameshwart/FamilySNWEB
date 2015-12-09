<?php 

require_once('DB/DB.php');

if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['c_password'])){
	$email = $_POST['email'];
	$password = $_POST['password'];
	$c_password = $_POST['c_password'];
	
	$result = '';

	if($email == "" || $password == ""){
		
		$result = json_encode(array("error"=>"Please enter email and password."));

	}else if($password != $c_password){
	
		$result = json_encode(array("error"=>"Password don't match."));	
		
	}else{
		$columnAndValues = array(
			'email'		=> $email,
			'password'	=> $password
		);
		$result = $db->insert('tbl_users',$columnAndValues)->execute();

	}

	echo json_encode($result);
}else {
	echo json_encode(array("error"=>"emptyAll"));
}