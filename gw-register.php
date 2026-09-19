<?php
session_start();
require_once 'gw-connect.php';
require_once 'gw-security.php';

if (!empty($_SESSION['userid'])) { header('Location: gw-index.php'); exit; }

$con=new mysqli(DATABASE_HOST,DATABASE_USER,DATABASE_PASS,DATABASE_NAME);
if($con->connect_errno){error_log('Database connection failed: '.$con->connect_error);exit('A database error occurred.');}
$con->set_charset('utf8mb4');
$errorMsg='';
$clientIp=gw_client_ip();
$registerIpKey=gw_throttle_key('register-ip',$clientIp);
gw_throttle_cleanup($con);

if($_SERVER['REQUEST_METHOD']==='POST'){
    gw_require_csrf();
    if(gw_throttle_is_blocked($con,'register-ip',$registerIpKey)){
        $con->close();
        gw_rate_limited_response();
    }
    gw_throttle_record_failure($con,'register-ip',$registerIpKey,GW_REGISTER_IP_LIMIT);
    $username=trim($_POST['username']??'');
    $email=trim($_POST['email']??'');
    $password=$_POST['password']??'';
    $confirm=$_POST['confirm_password']??'';

    if($username===''||strlen($username)>50||!preg_match('/^[A-Za-z0-9_.-]+$/',$username))$errorMsg='Username must be 1-50 characters and use only letters, numbers, dots, underscores, or hyphens.';
    elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($email)>255)$errorMsg='Please enter a valid email address.';
    elseif(strlen($password)<10)$errorMsg='Password must be at least 10 characters long.';
    elseif(strlen($password)>1024)$errorMsg='Password is too long.';
    elseif($password!==$confirm)$errorMsg='Passwords do not match.';
    else{
        $stmt=$con->prepare('SELECT 1 FROM users WHERE username=? OR email=? LIMIT 1');
        $stmt->bind_param('ss',$username,$email);$stmt->execute();$exists=(bool)$stmt->get_result()->fetch_row();$stmt->close();
        if($exists)$errorMsg='That username or email address is already registered.';
        else{
            $hash=password_hash($password,PASSWORD_DEFAULT);
            $stmt=$con->prepare('INSERT INTO users (username,password,email,access) VALUES (?,?,?,0)');
            $stmt->bind_param('sss',$username,$hash,$email);
            if($stmt->execute()){
                $userid=(int)$stmt->insert_id;$stmt->close();
                session_regenerate_id(true);
                $_SESSION['authenticated_at']=time();
                $_SESSION['last_activity']=time();
                $_SESSION['username']=$username;$_SESSION['userid']=$userid;$_SESSION['access']=0;
                unset($_SESSION['playerid'],$_SESSION['profcolor']);
                $con->close();header('Location: gw-index.php');exit;
            }
            error_log('Account creation failed: '.$stmt->error);$stmt->close();$errorMsg='An error occurred while creating your account.';
        }
    }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><link rel="stylesheet" type="text/css" href="gw-style.css"><title>Create Account</title>
<style>.account-wrap{max-width:520px;margin:35px auto;padding:0 20px}.account-card{background:rgba(255,255,255,.78);border:1px solid #bbb;border-radius:6px;padding:24px 28px;box-shadow:0 2px 8px rgba(0,0,0,.08)}.account-card h2{text-align:center;margin:0 0 22px}.account-field{display:grid;grid-template-columns:145px 1fr;gap:14px;align-items:center;margin-bottom:16px}.account-field label{float:none;width:auto;margin:0;padding:0;text-align:right}.account-field input{box-sizing:border-box;font:inherit;padding:7px 8px;width:100%}.account-actions,.account-footer{text-align:center}.account-actions{margin-top:22px}.account-actions input{font-size:1rem;padding:8px 16px}.account-footer{margin-top:22px}.account-error{text-align:center;color:#a00000}@media(max-width:560px){.account-field{grid-template-columns:1fr;gap:6px}.account-field label{text-align:left}.account-card{padding:20px}}</style></head><body>
<?php require 'gw-header.php'; ?>
<main class="account-wrap"><section class="account-card"><h2>Create Account</h2>
<?php if($errorMsg): ?><p class="account-error"><?php echo htmlspecialchars($errorMsg,ENT_QUOTES,'UTF-8'); ?></p><?php endif; ?>
<form method="POST" action="gw-register.php"><?php echo gw_csrf_input(); ?>
<div class="account-field"><label for="username">Username:</label><input id="username" name="username" maxlength="50" autocomplete="username" required value="<?php echo htmlspecialchars($_POST['username']??'',ENT_QUOTES,'UTF-8'); ?>"></div>
<div class="account-field"><label for="email">Email:</label><input type="email" id="email" name="email" maxlength="255" autocomplete="email" required value="<?php echo htmlspecialchars($_POST['email']??'',ENT_QUOTES,'UTF-8'); ?>"></div>
<div class="account-field"><label for="password">Password:</label><input type="password" id="password" name="password" minlength="10" maxlength="1024" autocomplete="new-password" required></div>
<div class="account-field"><label for="confirm_password">Confirm password:</label><input type="password" id="confirm_password" name="confirm_password" minlength="10" maxlength="1024" autocomplete="new-password" required></div>
<div class="account-actions"><input type="submit" value="Create account"></div></form>
<div class="account-footer">Already have an account? <a class="navlink" href="gw-index.php">Sign in</a></div>
</section></main></body></html>
<?php $con->close(); ?>
