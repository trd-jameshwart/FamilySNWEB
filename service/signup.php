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

		$userExist = $db->select('tbl_users')->where('email =',$email)->execute();

		if($userExist->rowCount >= 1){
			$result = json_encode(array("error"=>"Email is already in used!"));
		}else{

			$columnAndValues = array(
				'email'		=> $email,
				'password'	=> $password
			);
			$db = new DB();
			$dbInsert = $db->insert('tbl_users',$columnAndValues)->execute();
			if($dbInsert->result){
				$result = json_encode(array("OK"=>$dbInsert->result));
			}else {
				$result = json_encode(array("error"=>"Saving Error!Please try again."));
			}

		}
	}

	echo $result;

}else {
	echo json_encode(array("error"=>"Please enter email and password.!"));
}