<html>
<head>
<title> LINE LIFF APP</title>
</head>
<body>

<form action="upload.php" method="post" enctype="multipart/form-data">  
   <input type="file" name="uploadedfile" accept="image/*" capture>  
   <input type="submit" value="Upload">  
</form>


<?



date_default_timezone_set("Asia/Bangkok");
header('Content-Type: text/html; charset=utf-8');

	ini_set('display_errors', 1);
	error_reporting(~0);

    $serverName = "localhost";
    $userName = "root";
    $userPassword = "P@ssw0rd";
    $dbName = "PM2019";

   $conn = mysqli_connect($serverName,$userName,$userPassword,$dbName);

   $sql = "SELECT * FROM user ";

   $query = mysqli_query($conn,$sql);

?>
<table width="600" border="1">
  <tr>
    <th width="91"> <div align="center">CustomerID </div></th>
    <th width="98"> <div align="center">Name </div></th>
    <th width="198"> <div align="center">Email </div></th>
    <th width="97"> <div align="center">CountryCode </div></th>
    <th width="59"> <div align="center">Budget </div></th>
    <th width="71"> <div align="center">Used </div></th>
	<th width="71"> <div align="center">Detail </div></th>
  </tr>
<?php
while($result=mysqli_fetch_array($query,MYSQLI_ASSOC))
{
?>
  <tr>
    <td><div align="center"><?php echo $result["user_id"];?></div></td>
    <td><?php echo $result["user_username"];?></td>
    <td><?php echo $result["user_password"];?></td>
    <td><div align="center"><?php echo $result["user_latitude"];?></div></td>
    <td align="right"><?php echo $result["user_longtitude"];?></td>
    <td align="right"><?php echo $result["user_device"];?></td>
	  <td align="right"><a href="detail.php?user_lineid=U48b33a17fef7cd19edee238beb4d8c59<?php echo $result["user_lineid"];?>">Detail</a></td>
  </tr>

  
<?php


}
?>
</table>

<?php


$allowed_types=array('jpg','jpeg','gif','png');
$dir    ="botdata/U48b33a17fef7cd19edee238beb4d8c59/";
$files1 = scandir($dir);
foreach($files1 as $key=>$value){
    if($key>1){
        $file_parts = explode('.',$value);
        $filedate = date("Y-m-d H:i:s.",filemtime($dir."/".$value.""));
        $ext = strtolower(array_pop($file_parts));
        if(in_array($ext,$allowed_types)){
            echo "<img style='width:100px;' src='".$dir.$value."'/>&nbsp;";   
            echo "เข้างานตอน : $filedate<br>";

        }
 
    }
}
?>



<?php
mysqli_close($conn);
?>
</body>
</html>