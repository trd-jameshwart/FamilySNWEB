<?php

require_once('../index.php');
require_once('DB/DB.php');

$userId = $_POST["userId"];
$target_dir = UPLOADS_DIR."images";
if(!file_exists($target_dir)){
	echo mkdir($target_dir, 0777, true);
}
$imageFileName = $userId.time().basename($_FILES['file']['name']);
$target_dir = $target_dir . "/" . $imageFileName;

if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir)) {
	
	$columns = array('id','email','cover_photo','profile_photo');
	$userDetails = $db->select('tbl_users',$columns)->where('id = ',$userId)->execute();
	$userProfile = str_replace(BASE_URL, '..', $userDetails->result[0]['cover_photo']);
	
	$coverPhoto  = array('cover_photo'=>UPLOADS_URL.'images/'.$imageFileName);
	$update_user = $db->update('tbl_users',$coverPhoto)->where('id = ',$userId)->execute();

	if($update_user->rowCount == 1){
		//delete the last profile picture of the user
		unlink($userProfile);
		echo json_encode([
			"Message" => "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.",
			"Status" => "OK",
			"userId" => $_REQUEST["userId"]
		]);
	}
	

} else {

	echo json_encode([
		"Message" => "Sorry, there was an error uploading your file.",
		"Status" => "Error",
		"userId" => $_REQUEST["userId"]
	]);

}