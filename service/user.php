<?php 

require_once('../index.php');
require_once('DB/DB.php');

if(isset($_POST['user_id'])){
	
	if(!empty($_POST['user_id'])){
		$columns = array('id','email','cover_photo','profile_photo');
		$userDetails = $db->select('tbl_users',$columns)->where('id =',$_POST['user_id'])->execute();
		
		if(1 == $userDetails->rowCount){
			echo json_encode($userDetails->result[0]);
		}

	}
}

if(isset($_POST['id'],$_POST['email'])){
	
	$userDetails = $db->update('tbl_users',array('email'=>$_POST['email']))->where(' id = ',$_POST['id'])->execute();
	
	if(1 == $userDetails->rowCount){
		echo json_encode(array("result"=>"true"));
	}else{
		echo json_encode(array("result"=>"false"));
	}
}

if(isset($_POST['id'],$_POST['password'])){
   $userDetails = $db->update('tbl_users',array('password'=>$_POST['password']))->where(' id = ',$_POST['id'])->execute();
	
	if(1 == $userDetails->rowCount){
		echo json_encode(array("result"=>"true"));
	}else{
		echo json_encode(array("result"=>"false"));
	}

}