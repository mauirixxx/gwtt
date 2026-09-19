<?php
session_start();
require_once 'gw-connect.php';
require_once 'gw-security.php';
if(empty($_SESSION['userid'])){header('Location: gw-index.php');exit;}
$con=new mysqli(DATABASE_HOST,DATABASE_USER,DATABASE_PASS,DATABASE_NAME);
if($con->connect_errno){error_log('Database connection failed: '.$con->connect_error);exit('A database error occurred.');}
$con->set_charset('utf8mb4');
$userid=(int)$_SESSION['userid'];$errorMsg='';$successMsg='';
function getColorArray(){return ["#FFF","#DDD","#FF8","#CF9","#ACF","#9FC","#DAF","#FBB","#FCE","#BFF","#FC9","#DDF"];}
if($_SERVER['REQUEST_METHOD']==='POST'&&($_POST['docreate']??'')==='1'){
gw_require_csrf();$cname=trim($_POST['cname']??'');$bdate=trim($_POST['bdate']??'');$profid=(int)($_POST['professionid']??0);
if($cname===''||strlen($cname)>30)$errorMsg='Please enter a valid character name.';
elseif(!gw_valid_date($bdate))$errorMsg='Please enter a valid date.';
elseif(!gw_lookup_exists($con,'listruneprofessions','runeprofid',$profid))$errorMsg='Please choose a valid profession.';
else{$colors=getColorArray();$profcolor=$colors[$profid]??'#FFFFFF';$stmt=$con->prepare('INSERT INTO playername (charname,birthdate,userid,professionid,profcolor) VALUES (?,?,?,?,?)');$stmt->bind_param('ssiis',$cname,$bdate,$userid,$profid,$profcolor);if($stmt->execute()){$successMsg='Character created successfully!';}else{error_log('Insert error: '.$stmt->error);$errorMsg='An error occurred while creating your character.';}$stmt->close();}
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><link rel="stylesheet" type="text/css" href="gw-style.css"><title>Character Creation</title>
<style>
.create-wrap{max-width:520px;margin:35px auto;padding:0 20px}.create-card{background:rgba(255,255,255,.78);border:1px solid #bbb;border-radius:6px;padding:24px 28px;box-shadow:0 2px 8px rgba(0,0,0,.08)}.create-card h2{margin:0 0 22px;text-align:center}.create-field{display:grid;grid-template-columns:140px 1fr;gap:14px;align-items:center;margin-bottom:16px}.create-field label{float:none;width:auto;margin:0;padding:0;text-align:right}.create-field input,.create-field select{box-sizing:border-box;font:inherit;padding:7px 8px;width:100%}.create-actions{text-align:center;margin-top:22px}.create-actions input{font-size:1rem;padding:8px 16px}.create-message{text-align:center}.create-footer{text-align:center;margin-top:22px}.create-footer form{margin-top:16px}@media(max-width:560px){.create-field{grid-template-columns:1fr;gap:6px}.create-field label{text-align:left}.create-card{padding:20px}}
</style></head><body>
<?php require 'gw-header.php'; ?>
<main class="create-wrap"><section class="create-card"><h2>Create Character</h2>
<?php if($successMsg): ?><div class="create-message"><p><?php echo htmlspecialchars($successMsg,ENT_QUOTES,'UTF-8'); ?></p><p><a href="gw-toon.php" class="navlink">Continue to character selection</a></p></div>
<?php else: ?><?php if($errorMsg): ?><p class="create-message" style="color:#a00000"><?php echo htmlspecialchars($errorMsg,ENT_QUOTES,'UTF-8'); ?></p><?php endif; ?>
<form method="POST" action="gw-create.php"><?php echo gw_csrf_input(); ?><input type="hidden" name="docreate" value="1">
<div class="create-field"><label for="cname">Character name:</label><input type="text" id="cname" name="cname" maxlength="30" required autofocus></div>
<div class="create-field"><label for="bdate">Birthdate:</label><input id="bdate" name="bdate" type="date" required></div>
<div class="create-field"><label for="professionid">Profession:</label><select id="professionid" name="professionid" required><option selected disabled value="">Choose Profession</option>
<?php $r=$con->query('SELECT runeprofid,runeprofession FROM listruneprofessions WHERE runeprofid BETWEEN 2 AND 11 ORDER BY runeprofid');while($x=$r->fetch_assoc())echo '<option value="'.(int)$x['runeprofid'].'">'.htmlspecialchars($x['runeprofession'],ENT_QUOTES,'UTF-8').'</option>';$r->close(); ?>
</select></div><div class="create-actions"><input type="submit" value="Create character"></div></form><?php endif; ?>
<div class="create-footer"><a href="gw-toon.php" class="navlink">Return to character selection</a>
<form method="POST" action="gw-logout.php"><?php echo gw_csrf_input(); ?><input type="hidden" name="logout" value="1"><input type="submit" value="Logout"></form></div>
</section></main></body></html>
<?php $con->close(); ?>
