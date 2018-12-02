<?php
	session_start();
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	header('Access-Control-Allow-Methods: POST');
	header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
	
	$username = null;
	$password = null;
	
	include_once '../config/Database.php';
		$here = "hi";
	if((empty($_SESSION["authenticated"])) && ($_SESSION["authenticated"] != 'true') && ($_SESSION["authenticated"] != 'false')){
		$_SESSION['times'] = 0;
		unset($_SESSION['last_login_time']);
		$here .= " shistus1";
	}
		
	
	
	// Instantiate DB & connect
	$database = new Database();	
	$db = $database->connect();

	if (isset($_SESSION['last_login_time'])){
		if (time() - $_SESSION['last_login_time'] < 1*60*60) 
			$_SESSION['times'] = 0;
		$here .= " shistus2";
	}
	

	if ($_SESSION['times'] < 3) {
		$_SESSION['times'] += 1;
		
		$data = json_decode(file_get_contents("php://input"));
				

		$username = htmlspecialchars(strip_tags($data->username)); 
		$password = htmlspecialchars(strip_tags($data->password)); 
		if (!empty($username) && !empty($password)) {
			if (validateUser($username, $password, $db)) {
				$_SESSION["authenticated"] = 'true';
				//echo "<script> window.location.replace('map.html') </script>";
				//header('Location: ../front_end/map.html');
				echo json_encode(
					array('message' => 'Connected!', 'stat' => true)
				);
				
			} else {
				$_SESSION["authenticated"] = 'false';
				echo json_encode(
					array('message' => 'Something went wrong. Try again!' . $here, 'stat' => false)
				);
			}
		} else {
			$_SESSION["authenticated"] = 'false';
						echo json_encode(
				array('message' => 'Something went wrong. Try again!', 'stat' => false)
			);
		}
	} else {
			$_SESSION["authenticated"] = 'false';
			if (!isset($_SESSION['last_login_time']))
				$_SESSION['last_login_time'] = time();
			echo json_encode(
				array('message' => 'You have exceeded the maximum number of attempts. Try again later', 'stat' => false)
			);
	}
	
	function test() { return true;}
	
		/*
		

		
		if (!empty($username) && !empty($password)) {
			if (validateUser($username, $password, $db)) {
				$_SESSION["authenticated"] = 'true';
				header('Location: ../front_end/map.html');
			} else {
				$_SESSION["authenticated"] = 'false';
				$_SESSION["times"] = $_SESSION["times"] + 1;
				echo json_encode(
					array('message' => 'Something went wrong. Try again!')
				);
			}
		} 
		else {
			$_SESSION["authenticated"] = 'false';
			$_SESSION["times"] = $_SESSION["times"] + 1;

		}
		
	} else {

	}
	*/
	
	function validateUser($username, $password, $conn) {
		$dbstoredpassword = null;
		$query = "SELECT password FROM USER WHERE username = '" . $username . "' ";
		$stmt = $conn->prepare($query);
		
		$stmt->execute();
		$num = $stmt->rowCount();
		if($num > 0) {
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$dbstoredpassword = $row['password'];
			if ($dbstoredpassword == $password){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
?>