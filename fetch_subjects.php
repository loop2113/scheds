<?php

include 'db.php';

$id = $_POST['id'];
$sql = "SELECT * FROM subjects WHERE course_id = $id";
$result = mysqli_query($conn, $sql);

$out = '';
while($row = mysqli_fetch_assoc($result))
{
    $out .= '<option value='.$row['id'].'>'.$row['subject'].' - '. $row['description'].'</option>';
}
echo $out;

?>