<?php include 'config.php';
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
$msg="";
if(isset($_POST['add'])){
 $phone=trim($_POST['phone']); $email=trim($_POST['email']);
 // SERVER SIDE VALIDATION
 if(!preg_match('/^[0-9]{10}$/', $phone)){
   $msg="❌ Phone must be exactly 10 digits (e.g. 9876543210)";
 } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with(strtolower($email), '@gmail.com')){
   $msg="❌ Email must be @gmail.com only";
 } else {
   $stmt=$conn->prepare("INSERT INTO donors (name,blood_group,age,phone,email,address,last_donation_date,user_id) VALUES (?,?,?,?,?,?,?,?)");
   $stmt->bind_param("ssissssi",$_POST['name'],$_POST['bg'],$_POST['age'],$_POST['phone'],$_POST['email'],$_POST['city'],$_POST['last'],$_SESSION['user_id']);
   if($stmt->execute()) $msg="✅ Donor Added - Visible to everyone now!"; else $msg="Error: ".$conn->error;
 }
}
?>
<!DOCTYPE html><html><head><link rel="stylesheet" href="css/style.css"></head><body>
<div class="header"><h3>🩸 LifeLink User</h3><div>Hi, <?php echo $_SESSION['username']; ?> <a href="index.php">Home</a> <?php if($_SESSION['role']=='admin') echo '<a href="admin.php">Admin</a>'; ?> <a href="logout.php">Logout</a></div></div>
<div class="container">
<?php if($msg) echo "<p style='padding:12px;border-radius:10px;background:".(str_contains($msg,'✅')?'#dcfce7':'#fee2e2')."'>$msg</p>"; ?>
<div style="display:flex;gap:20px;flex-wrap:wrap;margin-top:15px">
<div class="form-card" style="flex:1;min-width:320px"><h3>➕ Add New Donor</h3>
<form method="POST" onsubmit="return validateDonor(this)">
<input name="name" placeholder="Full Name" required>
<select name="bg" required style="margin-top:6px"><option value="">Blood Group</option><option>A+</option><option>A-</option><option>B+</option><option>B-</option><option>O+</option><option>O-</option><option>AB+</option><option>AB-</option></select>
<input name="age" type="number" placeholder="Age" required style="margin-top:6px">
<input name="phone" placeholder="Phone - 10 digits only" required pattern="[0-9]{10}" maxlength="10" title="Exactly 10 digits" style="margin-top:6px">
<input name="email" placeholder="Email - only @gmail.com" required pattern=".*@gmail\.com$" title="Must end with @gmail.com" style="margin-top:6px">
<input name="city" placeholder="City (mapsa)" required style="margin-top:6px">
<input name="last" type="date" style="margin-top:6px">
<button name="add" class="btn" style="width:100%;margin-top:10px">Add Donor</button>
</form>
<script>
function validateDonor(f){
 let ph=f.phone.value, em=f.email.value;
 if(!/^[0-9]{10}$/.test(ph)){ alert('Phone must be exactly 10 digits'); return false; }
 if(!em.toLowerCase().endsWith('@gmail.com')){ alert('Email must be @gmail.com only'); return false; }
 return true;
}
</script>
</div>
<div style="flex:2;min-width:450px"><h3>👁️ All Donors</h3>
<?php $r=$conn->query("SELECT * FROM donors ORDER BY id DESC"); echo '<div class="donor-grid" style="margin-top:10px">'; while($d=$r->fetch_assoc()) echo "<div class='donor-card'><div class='blood-badge'>{$d['blood_group']}</div><h3>{$d['name']}</h3><p>📞 {$d['phone']} (10-digit)</p><p>📧 {$d['email']}</p><p>📍 {$d['address']}</p></div>"; echo '</div>'; ?>
</div></div></div></body></html>