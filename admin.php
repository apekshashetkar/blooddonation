<?php include 'config.php';
if(!isset($_SESSION['user_id']) || ($_SESSION['role']??'')!='admin'){ header("Location: login.php"); exit; }

$msg="";
// DELETE
if(isset($_GET['delete'])){ 
 $id=(int)$_GET['delete']; 
 $conn->query("DELETE FROM donors WHERE id=$id"); 
 $msg="✅ Donor Deleted";
}

// VALIDATION FUNCTION
function validateData($phone, $email){
 if(!preg_match('/^[0-9]{10}$/', $phone)){
   return "❌ Phone must be exactly 10 digits (e.g. 9876543210)";
 }
 if(!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with(strtolower($email), '@gmail.com')){
   return "❌ Email must be @gmail.com only (e.g. test@gmail.com)";
 }
 return "";
}

// ADD
if(isset($_POST['add'])){
 $err = validateData($_POST['phone'], $_POST['email']);
 if($err!=""){
   $msg=$err;
 } else {
   $stmt=$conn->prepare("INSERT INTO donors (name,blood_group,age,phone,email,address,last_donation_date,user_id) VALUES (?,?,?,?,?,?,?,?)");
   $stmt->bind_param("ssissssi",$_POST['name'],$_POST['bg'],$_POST['age'],$_POST['phone'],$_POST['email'],$_POST['city'],$_POST['last'],$_SESSION['user_id']); 
   $stmt->execute();
   $msg="✅ Donor Added Successfully!";
 }
}

// UPDATE
if(isset($_POST['update'])){
 $err = validateData($_POST['phone'], $_POST['email']);
 if($err!=""){
   $msg=$err;
 } else {
   $stmt=$conn->prepare("UPDATE donors SET name=?,blood_group=?,age=?,phone=?,email=?,address=?,last_donation_date=? WHERE id=?");
   $stmt->bind_param("ssissssi",$_POST['name'],$_POST['bg'],$_POST['age'],$_POST['phone'],$_POST['email'],$_POST['city'],$_POST['last'],$_POST['id']); 
   $stmt->execute();
   $msg="✅ Donor Updated Successfully!";
 }
}

$edit=null; 
if(isset($_GET['edit'])) $edit=$conn->query("SELECT * FROM donors WHERE id=".(int)$_GET['edit'])->fetch_assoc();
?>
<!DOCTYPE html><html><head><title>Admin Panel - LifeLink</title><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="stylesheet" href="css/style.css"></head><body>
<div class="header"><h3>🔴 ADMIN PANEL - LifeLink</h3><div><a href="index.php">Public Home</a><a href="dashboard.php">User View</a><a href="logout.php">Logout</a></div></div>
<div class="container">
<?php if($msg) echo "<p style='padding:12px;border-radius:10px;margin-bottom:15px;background:".(str_contains($msg,'✅')?'#dcfce7':'#fee2e2').";border:1px solid ".(str_contains($msg,'✅')?'#16a34a':'#dc2626')."'>$msg</p>"; ?>
<div style="display:flex;gap:20px;flex-wrap:wrap">
<div class="form-card" style="flex:1;min-width:330px"><h3><?php echo $edit?"✏️ Update Donor #".$edit['id']:"➕ Add New Donor"; ?></h3>
<form method="POST" onsubmit="return validateAdmin(this)">
<?php if($edit) echo '<input type="hidden" name="id" value="'.$edit['id'].'">'; ?>
<input name="name" value="<?php echo $edit['name']??''; ?>" placeholder="Full Name" required>
<select name="bg" required style="margin-top:6px"><option value="">Blood Group</option><?php foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $b){ $s=($edit && $edit['blood_group']==$b)?'selected':''; echo "<option $s>$b</option>"; } ?></select>
<input name="age" type="number" value="<?php echo $edit['age']??''; ?>" placeholder="Age" required style="margin-top:6px">
<input name="phone" value="<?php echo $edit['phone']??''; ?>" placeholder="Phone - Exactly 10 digits" required pattern="[0-9]{10}" maxlength="10" minlength="10" title="Exactly 10 digits only, e.g. 9876543210" style="margin-top:6px">
<input name="email" value="<?php echo $edit['email']??''; ?>" placeholder="Email - only @gmail.com" required pattern=".*@gmail\.com$" title="Must end with @gmail.com" style="margin-top:6px">
<input name="city" value="<?php echo $edit['address']??''; ?>" placeholder="City (mapsa)" required style="margin-top:6px">
<input name="last" type="date" value="<?php echo $edit['last_donation_date']??''; ?>" style="margin-top:6px">
<button name="<?php echo $edit?'update':'add'; ?>" class="btn" style="width:100%;margin-top:12px;background:<?php echo $edit?'#16a34a':''; ?>"><?php echo $edit?'✅ Update Donor':'➕ Add Donor'; ?></button>
<?php if($edit) echo '<a href="admin.php" class="btn btn-gray" style="width:100%;text-align:center;margin-top:8px">Cancel Edit</a>'; ?>
</form>
<script>
function validateAdmin(f){
 let ph = f.phone.value.trim();
 let em = f.email.value.trim().toLowerCase();
 if(!/^[0-9]{10}$/.test(ph)){
   alert('❌ Phone must be exactly 10 digits!\nExample: 9876543210');
   f.phone.focus();
   return false;
 }
 if(!em.endsWith('@gmail.com')){
   alert('❌ Email must be @gmail.com only!\nExample: test@gmail.com');
   f.email.focus();
   return false;
 }
 return true;
}
</script>
</div>
<div style="flex:2;min-width:520px"><h3>📋 All Donors - Edit / Delete</h3>
<table><tr><th>ID</th><th>Name</th><th>Blood</th><th>Phone (10-digit)</th><th>Email</th><th>City</th><th>Action</th></tr>
<?php $r=$conn->query("SELECT * FROM donors ORDER BY id DESC"); 
while($d=$r->fetch_assoc()){ 
 echo "<tr><td>{$d['id']}</td><td>{$d['name']}</td><td><b style='color:#dc2626'>{$d['blood_group']}</b></td><td>{$d['phone']}</td><td style='font-size:12px'>{$d['email']}</td><td>{$d['address']}</td><td><a href='admin.php?edit={$d['id']}' class='btn' style='padding:5px 10px;font-size:12px'>Edit</a> <a href='admin.php?delete={$d['id']}' class='btn' style='padding:5px 10px;font-size:12px;background:#dc2626' onclick='return confirm(\"Delete donor {$d['name']}?\")'>Delete</a></td></tr>"; 
} ?>
</table>
<br><h3>👥 All Users</h3>
<table><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th></tr>
<?php $u=$conn->query("SELECT id,username,email,role FROM users ORDER BY id DESC"); 
while($row=$u->fetch_assoc()) echo "<tr><td>{$row['id']}</td><td>{$row['username']}</td><td>{$row['email']}</td><td><span style='background:".($row['role']=='admin'?'#dc2626':'#16a34a').";color:white;padding:3px 8px;border-radius:10px;font-size:12px'>{$row['role']}</span></td></tr>"; ?>
</table>
</div></div></div></body></html>