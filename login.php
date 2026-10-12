<?php include 'config.php'; $msg="";
if($_SERVER['REQUEST_METHOD']=='POST'){
 $u=$_POST['username']; $p=$_POST['password'];
 $res=$conn->query("SELECT * FROM users WHERE username='$u' LIMIT 1");
 if($row=$res->fetch_assoc()){
   if(password_verify($p,$row['password']) || $p==$row['password']){
     $_SESSION['user_id']=$row['id']; $_SESSION['username']=$row['username']; $_SESSION['role']=$row['role'];
     if($row['role']=='admin') header("Location: admin.php"); else header("Location: dashboard.php"); exit;
   }else $msg="Wrong password";
 }else $msg="User not found";
}
?>
<!DOCTYPE html><html><head><link rel="stylesheet" href="css/style.css"></head><body>
<div class="login-wrapper"><div class="login-card"><h2>🩸 Login - LifeLink</h2><p style="color:red"><?php echo $msg; ?></p>
<form method="POST"><input name="username" placeholder="adminuser / NEHA / Apeksha" required><input name="password" type="password" placeholder="Password" required style="margin-top:10px">
<button class="btn" style="width:100%;margin-top:15px">Login</button></form>
<p style="margin-top:12px;font-size:13px">Admin: adminuser / admin123 & Apeksha / apek<br>User: NEHA / your pass</p><a href="index.php">← Creative Home - View Donors</a></div></div></body></html>