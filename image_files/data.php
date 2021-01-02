<?php
header('Content-Type: application/json');
include("envVariables.php");

$conn = mysqli_connect(DATABASENAME,DATABASEUSER,DATABASEPSWD,DATABASEDB);

$sqlQuery_devices = "SELECT * FROM devices ORDER BY dev_id";
$result_devices = mysqli_query($conn,$sqlQuery_devices);

$data_sensors = array();
foreach ($result_devices as $row) {
	$data_sensors[] = $row;
}
mysqli_close($conn);

echo json_encode($data_sensors);
?>
