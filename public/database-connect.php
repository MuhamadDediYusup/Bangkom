<?php
$hostname = '192.168.90.190';
$username = 'simpeg2015';
$password = 'simpeg2015';
$database = 'simpeg2015';

$con = new mysqli($hostname, $username, $password, $database);
if ($con->connect_errno) {
    die('Error ' . $this->con->connect_error);
}
echo 'Connected successfully!';

$query = "SELECT * FROM pegawai WHERE nip = '197701221996031002'";
$result = $con->query($query);
print_r($result->fetch_assoc());

$con->close();
