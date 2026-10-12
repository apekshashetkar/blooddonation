<?php include 'config.php'; ?>
<!DOCTYPE html><html><head><title>LifeLink - Home</title><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="stylesheet" href="css/style.css"></head><body>
<div class="header"><h3>🩸 LifeLink Blood Management</h3><div>
<?php if(isset($_SESSION['username'])){ echo htmlspecialchars($_SESSION['username'])." (".$_SESSION['role'].")"; ?>
<a href="dashboard.php">Dashboard</a><?php if($_SESSION['role']=='admin'){ ?><a href="admin.php">Admin Panel</a><?php } ?><a href="logout.php">Logout</a>
<?php }else{ ?><a href="login.php">Login</a> <a href="signup.php">Sign Up</a><?php } ?>
</div></div>
<div class="hero"><h1>Find Blood Donors Near You</h1><p>✨ Creative Public Portal - Everyone can view donors without login - Save lives with style</p></div>
<div class="container">
<div class="search-card">
<form method="GET" style="display:flex;gap:10px;flex:1;flex-wrap:wrap">
<select name="bg"><option value="">All Blood Groups</option><option>A+</option><option>A-</option><option>B+</option><option>B-</option><option>O+</option><option>O-</option><option>AB+</option><option>AB-</option></select>
<input name="city" placeholder="🔍 Search by City (mapsa)" value="<?php echo $_GET['city']??''; ?>">
<button class="btn">Search</button><a href="index.php" class="btn btn-gray">Clear</a>
</form>
</div>
<?php
$sql="SELECT * FROM donors WHERE 1=1";
if(!empty($_GET['bg'])) $sql.=" AND blood_group='".$conn->real_escape_string($_GET['bg'])."'";
if(!empty($_GET['city'])) $sql.=" AND address LIKE '%".$conn->real_escape_string($_GET['city'])."%'";
$sql.=" ORDER BY id DESC";
$res=$conn->query($sql);
echo "<h3>✨ ".$res->num_rows." Donor Cards - Public View (No Login Needed)</h3>";
echo '<div class="donor-grid">';
while($r=$res->fetch_assoc()){
 echo "<div class='donor-card'><div class='blood-badge'>{$r['blood_group']}</div><h3>👤 {$r['name']}</h3><p>🎂 Age: {$r['age']}</p><p>📞 {$r['phone']}</p><p>📧 {$r['email']}</p><p>📍 {$r['address']}</p><p>📅 Last Donated: {$r['last_donation_date']}</p></div>";
}
echo '</div>'; ?>
</div></body></html>