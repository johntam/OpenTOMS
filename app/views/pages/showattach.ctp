<?php
$mysqli = new mysqli("asapdb01.cqezga1cxvxz.us-east-1.rds.amazonaws.com","asapuser","templ88","ASAPDB01");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$id = $this->params['pass']['0'];
$query = "SELECT name, size, content, type FROM attachments WHERE id=".$id;
$result = $mysqli->query($query);

$row = $result->fetch_array(MYSQLI_ASSOC);
$data = base64_decode($row['content']);
$name = $row['name'];
$size = $row['size'];
$type = $row['type'];

/* free result set */
$result->free();

/* close connection */
$mysqli->close();

header("Content-type: $type");
header("Content-length: $size");
header("Content-Disposition: attachment; filename=$name");
header("Content-Description: PHP Generated Data");
echo $data;