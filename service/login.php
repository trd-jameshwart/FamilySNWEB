<?php 

//Login Script

require_once('DB/DB.php');

if(isset($_POST['email']) && isset($_POST['password'])){
	$email = $_POST['email'];
	$pword = $_POST['password'];

	if($email !=""  && $pword != ""){
		$columns = array('id','email','cover_photo','profile_photo');
		$user = $db->select('tbl_users',$columns)->where("email =",$email)->and_where("password =",$pword)->execute();
		
		if($db->rowCount == 1){
			$result = array(
				array("OK"=>true),
				$user->result[0]
			);

			echo json_encode($result);	
			
		} else {
			echo json_encode(array("error"=>"Username and password is incorrect"));
		}

	}else{
		echo json_encode(array("error"=>"Please enter email and password.james1!"));
	}

}else{
	echo json_encode(array("error"=>"Please enter email and password!"));
}