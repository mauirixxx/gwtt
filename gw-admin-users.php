<?php
session_start();
require_once 'gw-connect.php';
require_once 'gw-security.php';
$con=new mysqli(DATABASE_HOST,DATABASE_USER,DATABASE_PASS,DATABASE_NAME);
if($con->connect_errno){error_log('Database error: '.$con->connect_error);exit('A database error occurred.');}
$con->set_charset('utf8mb4');gw_require_admin($con);gw_refresh_session_access($con);$message='';$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 gw_require_csrf();$targetUserId=(int)($_POST['userid']??0);$newAccess=(int)($_POST['access']??-1);$currentUserId=(int)$_SESSION['userid'];$primaryAdminId=1;
 if($targetUserId<=0||!in_array($newAccess,[0,9],true))$error='Invalid user or access level.';
 elseif($targetUserId===$primaryAdminId&&$newAccess!==9)$error='The primary administrator cannot have administrator access revoked.';
 elseif($targetUserId===$currentUserId&&$newAccess===0)$error='You cannot revoke your own administrator access.';
 else{$stmt=$con->prepare('SELECT username FROM users WHERE userid=?');$stmt->bind_param('i',$targetUserId);$stmt->execute();$target=$stmt->get_result()->fetch_assoc();$stmt->close();
  if(!$target)$error='User not found.';
  else{$stmt=$con->prepare('UPDATE users SET access=? WHERE userid=?');$stmt->bind_param('ii',$newAccess,$targetUserId);
   if($stmt->execute())$message=$newAccess===9?'Administrator access granted to "'.$target['username'].'".':'Administrator access revoked from "'.$target['username'].'".';
   else{$error='Administrator access could not be updated.';error_log('Admin access update failed: '.$stmt->error);}$stmt->close();}
 }
}
$users=[];$r=$con->query('SELECT u.userid,u.username,u.email,u.access,(SELECT COUNT(*) FROM playername p WHERE p.userid=u.userid) AS character_count FROM users u ORDER BY u.username');while($row=$r->fetch_assoc())$users[]=$row;$r->close();
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><link rel="stylesheet" href="gw-style.css"><title>User Management</title>
<style>.admin-wrap{max-width:1100px;margin:30px auto;padding:0 20px}.admin-table{width:100%;border-collapse:collapse;background:rgba(255,255,255,.82)}.admin-table th,.admin-table td{padding:9px;border:1px solid #aaa;text-align:left}.admin-table form{margin:0}.admin-actions{display:flex;gap:6px;align-items:center}.admin-danger{background:#8b1e1e;color:#fff;border:0;padding:7px 10px;cursor:pointer}.admin-message{padding:10px;background:#e8f5e9}.admin-error{padding:10px;background:#ffebee}</style></head><body>
<?php require 'gw-header.php'; ?><main class="admin-wrap"><h2>User Management</h2>
<?php if($message): ?><p class="admin-message"><?php echo htmlspecialchars($message,ENT_QUOTES,'UTF-8'); ?></p><?php endif; ?><?php if($error): ?><p class="admin-error"><?php echo htmlspecialchars($error,ENT_QUOTES,'UTF-8'); ?></p><?php endif; ?>
<table class="admin-table"><thead><tr><th>Username</th><th>Email</th><th>Characters</th><th>Access</th><th>Actions</th></tr></thead><tbody>
<?php foreach($users as $u): ?><tr><td><?php echo htmlspecialchars($u['username'],ENT_QUOTES,'UTF-8'); ?></td><td><?php echo htmlspecialchars($u['email'],ENT_QUOTES,'UTF-8'); ?></td><td><?php echo (int)$u['character_count']; ?></td><td><?php echo (int)$u['access']===9?'<strong>Administrator</strong>':'Normal user'; ?></td><td class="admin-actions">
<?php if((int)$u['userid']===1): ?><strong>Primary administrator</strong>
<?php elseif((int)$u['userid']===(int)$_SESSION['userid']): ?>Current account
<?php elseif((int)$u['access']===9): ?><form method="POST" onsubmit="return confirm('Revoke administrator access from this user?');"><?php echo gw_csrf_input(); ?><input type="hidden" name="userid" value="<?php echo (int)$u['userid']; ?>"><input type="hidden" name="access" value="0"><button class="admin-danger" type="submit">Revoke admin</button></form>
<?php else: ?><form method="POST" onsubmit="return confirm('Grant administrator access to this user?');"><?php echo gw_csrf_input(); ?><input type="hidden" name="userid" value="<?php echo (int)$u['userid']; ?>"><input type="hidden" name="access" value="9"><button type="submit">Grant admin</button></form><?php endif; ?></td></tr><?php endforeach; ?>
</tbody></table></main></body></html><?php $con->close(); ?>
