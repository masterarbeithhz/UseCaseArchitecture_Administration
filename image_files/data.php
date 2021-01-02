<?php
header('Content-Type: application/json');

$conn = mysqli_connect("mysquc","root","Philipp1","testdb");
//$conn = mysqli_connect("localhost","root","philipp","testdb");

$sqlQuery_devices = "SELECT * FROM devices ORDER BY dev_id";
$result_devices = mysqli_query($conn,$sqlQuery_devices);

$data_sensors = array();
foreach ($result_devices as $row) {
	$data_sensors[] = $row;
}
mysqli_close($conn);

echo json_encode($data_sensors);
?>
