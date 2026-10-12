<?php include 'config.php'; $msg="";
if($_SERVER['REQUEST_METHOD']=='POST'){
 $u=trim($_POST['username']); $e=trim($_POST['email']); $p=$_POST['password'];
 // VALIDATION
 if(!preg_match('/^[0-9]{10}$/', $_POST['phone'] ?? '')) { 
   // phone field optional in signup, we skip
 }
 if(!filter_var($e, FILTER_VALIDATE_EMAIL) || !str_ends_with(strtolower($e), '@gmail.com')){
   $msg="<p style='color:red'>❌ Email must be @gmail.com only (e.g. test@gmail.com)</p>";
 } else {
   $hash=password_hash($p, PASSWORD_DEFAULT);
   $stmt=$conn->prepare("INSERT INTO users (username,email,password,role) VALUES (?,?,?, 'user')");
   $stmt->bind_param("sss",$u,$e,$hash);
   if($stmt->execute()) $msg="<p style='color:green'>Created! <a href='login.php'>Login now</a></p>"; else $msg="<p style='color:red'>Username/Email exists</p>";
 }
}
?>
<!DOCTYPE html><html><head><link rel="stylesheet" href="css/style.css"></head><body>
<div class="login-wrapper"><div class="login-card"><h2>Create Account</h2><?php echo $msg; ?>
<form method="POST" onsubmit="return validateSignup()">
<input name="username" placeholder="Username" required>
<input name="email" id="s_email" placeholder="Email - only @gmail.com" required pattern=".*@gmail\.com$" title="Must end with @gmail.com">
<input name="password" type="password" placeholder="Password" required style="margin-top:8px">
<button class="btn" style="width:100%;margin-top:15px">Sign Up</button></form>
<script>
function validateSignup(){
 let e=document.getElementById('s_email').value;
 if(!e.toLowerCase().endsWith('@gmail.com')){ alert('Email must be @gmail.com'); return false; }
 return true;
}
</script>
<p style="margin-top:12px"><a href="login.php">Already have account? Login</a> | <a href="index.php">Home</a></p></div></div></body></html>