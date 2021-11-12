<?php
include "config.php";
?>
<html>
	<head>
		<title>Bootstrap Example</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container">
			<p class="display-2 text-center">Send Notification</p>
			<form action="" method="post">
				<div class="form-group">
					<label for="ids">Select Email:</label>
					<select class="form-control" id="ids" name="Email" required>
					<option value="">Select Email</option>
					<?php
						if($result = $conn->query("SELECT * FROM app_users")){
							while($row = $result->fetch_assoc()){
								$email = $row['Email'];?>
								<option value="<?php echo $email; ?>"><?php echo $email; ?></option>
							<?php
							}
						}
					?>
					</select>
				</div>
				<div class="form-group">
					<label for="title">Email Title:</label>
					<input type="text" class="form-control" placeholder="Enter Title" name="title" id="title" required>
				</div>
				<div class="form-group">
					<label for="msg">Enter Message:</label>
					<input type="text" class="form-control" placeholder="Enter Message" name="message" id="msg"  required>
				</div>
				<button type="submit" name="submitbtn" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</body>
</html>

<?php
if(isset($_POST['submitbtn'])){
	$email = $_POST['Email'];
	$title = $_POST['title'];
	$message = $_POST['message'];
	$fetchToken = $conn->prepare("SELECT Token FROM app_users WHERE Email=?");
	$fetchToken->bind_param("s",$email);
	$fetchToken->execute();
	$fetchToken->store_result();
	$fetchToken->bind_result($token);
	$fetchToken->fetch();
	$fetchToken->close();
	$result = push_notification_android($token, $title, $message);
	$obj = json_decode($result);
	
	if($obj->success>0){ ?>
		<div class="container">
			<div class="alert alert-success alert-dismissible">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				Notification sent successfully!
			</div>
		</div>
	<?php
	} else {?>
		<div class="container">
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				Failed to send notification
			</div>
		</div>
	<?php
	}
}

function push_notification_android($device_id, $title, $message){
    //API URL of FCM
    $url = 'https://fcm.googleapis.com/fcm/send';

    /*api_key available in:
    Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    
	$api_key = 'AAAAyHwBLgg:APA91bED7eNOTND63C5o37peGrBRxVhP8zkqxdAOjV37NhSRcXQ1toyU5SUELROBMB9_3RTyd-mQAlq2Jd2_82VNjY-muBdSA0BxuJDWd4ldTynmyPee3z2buyqAbawDOnpKtnMwNrsf'; //Replace with yours
	
	$target = $device_id;
	
	$fields = array();
	$fields['priority'] = "high";
	$fields['notification'] = ["title" => $title, 
				    "body" => $message, 
				    'data' => ['message' => $message],
				    "sound" => "default"];
	if (is_array($target)){
	    $fields['registration_ids'] = $target;
	} else{
	    $fields['to'] = $target;
	}

	$encodedData = json_encode($fields);

    //header includes Content type and api key
    $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$api_key
    );
                
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

    $result = curl_exec($ch);

    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;
}
?>