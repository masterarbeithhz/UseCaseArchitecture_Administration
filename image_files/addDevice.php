<html>

<head>
	<title>add or update Device table</title>
</head>

<body>
	<?php
	include("envVariables.php");

	//Make sure that it is a POST request.
	if (strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0) {
		throw new Exception('Request method must be POST!');
	}

	//Make sure that the content type of the POST request has been set to application/json
	$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
	if (strcasecmp($contentType, 'application/json') != 0) {
		throw new Exception('Content type must be: application/json');
	}
	// Read the input stream
	$json = file_get_contents('php://input');
	$obj = json_decode($json);
	$action_type = $obj->type;
	$dev_id = $obj->dev_id;
	$dev_location_name = $obj->dev_location_name;
	$dev_latitude = $obj->dev_latitude;
	$dev_longitude = $obj->dev_longitude;


	$conn = mysqli_connect(DATABASENAME,DATABASEUSER,DATABASEPSWD,DATABASEDB);

	if ($conn->connect_error) {
		die("ERROR: Unable to connect: " . $conn->connect_error);
	}

	//INSERT INTO table (id, name, age) VALUES(1, "A", 19) ON DUPLICATE KEY UPDATE name="A", age=19

	// //checks if sensor id is listed in your devices table if not massage is ignored
	// $sqlQuery_devices = "SELECT dev_id from devices WHERE dev_Id='$dev_id'";
	// $result_devices = mysqli_query($conn, $sqlQuery_devices);
	// $data_sensors = array();
	// foreach ($result_devices as $row) {
	// 	$data_sensors[] = $row;
	// }

	if ($action_type === "add") {
		// anlegen eines neuen sensors
		$stmt = $conn->prepare("INSERT INTO devices (dev_id, dev_location_name, dev_latitude, dev_longitude) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("ssss", $dev_id, $dev_location_name, $dev_latitude, $dev_longitude);
		$stmt->execute();
		echo 'new device successfully created';
	}
	if ($action_type === "update") {
		// update des bestehenden sensors
		//$stmt = $conn->prepare("UPDATE devices SET content=? WHERE id=?");
		//$statement = $pdo->prepare("UPDATE users SET vorname = :vorname_neu, email = :email_neu, nachname = :nachname_neu WHERE id = :id");
		//$stmt = $conn->prepare("UPDATE devices SET (dev_location_name = $dev_location_name, dev_latitude = $dev_location_name, dev_longitude = $dev_longitude) WHERE dev_id = $dev_id");
		//$statement->execute(array('id' => 1, 'email_neu' => 'neu@php-einfach.de', 'vorname_neu' => 'Neuer Vorname', 'nachname_neu' => 'Neuer Nachname'));
		//$stmt = $conn->prepare("UPDATE devices SET (dev_location_name, dev_latitude, dev_longitude) VALUES (?, ?, ?) WHERE dev_id = ?");
		//$stmt->bind_param("ssss", $dev_location_name, $dev_latitude, $dev_longitude, $dev_id);
		//$stmt->execute();
		//$statement = $conn->prepare("UPDATE devices SET dev_locaition = :dev_location_neu, dev_longitude = :dev_longitude_neu, dev_latitude = :dev_latitude_neu WHERE dev_id = :dev_id");
		//$statement->execute(array('dev_id' => $dev_id, 'dev_locaiton_neu' => $dev_location_name, 'dev_longidute_neu' => $dev_longitude, 'dev_latitude_neu' => $dev_latitude));
		$stmt = $conn->prepare("DELETE FROM devices WHERE dev_id = ?");
		$stmt->bind_param("s", $dev_id);
		$stmt->execute();
		$stmt = $conn->prepare("INSERT INTO devices (dev_id, dev_location_name, dev_latitude, dev_longitude) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("ssss", $dev_id, $dev_location_name, $dev_latitude, $dev_longitude);
		$stmt->execute();
		echo 'device successfully updated';		
	}
	if ($action_type === "delete") {
		$stmt = $conn->prepare("DELETE FROM devices WHERE dev_id = ?");
		$stmt->bind_param("s", $dev_id);
		$stmt->execute();
	}




	$stmt->close();
	$conn->close();
	?>
</body>

</html>