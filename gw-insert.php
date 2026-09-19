<?php
session_start();
require_once 'gw-connect.php';
require_once 'gw-security.php';

if(empty($_SESSION['userid'])||empty($_SESSION['playerid'])){http_response_code(403);exit('Unauthorized access.');}
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: gw-toon.php');exit;}
gw_require_csrf();

$con=new mysqli(DATABASE_HOST,DATABASE_USER,DATABASE_PASS,DATABASE_NAME);
if($con->connect_errno){error_log('Database connection failed: '.$con->connect_error);exit('A database error occurred.');}
$con->set_charset('utf8mb4');
$uid=(int)$_SESSION['userid'];$toonid=(int)$_SESSION['playerid'];
if(!gw_character_belongs_to_user($con,$toonid,$uid)){unset($_SESSION['playerid'],$_SESSION['profcolor']);$con->close();http_response_code(403);exit('Invalid character context.');}

$gold=filter_input(INPUT_POST,'droppedgold',FILTER_VALIDATE_INT,['options'=>['min_range'=>0,'max_range'=>9999]]);
$droptype=filter_input(INPUT_POST,'droptype',FILTER_VALIDATE_INT,['options'=>['min_range'=>1,'max_range'=>4]]);
$locid=filter_input(INPUT_POST,'location',FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
$treasdate=trim($_POST['treasuredate']??'');
if($gold===false||$gold===null||$droptype===false||$droptype===null||$locid===false||$locid===null||!gw_valid_date($treasdate)||!gw_lookup_exists($con,'treasuredata','treasureid',(int)$locid)){
    $con->close();http_response_code(400);exit('Invalid drop data.');
}

if($droptype===1){
    $rarity=(int)($_POST['rare']??0);$req=(int)($_POST['requirement']??-1);$attrib=(int)($_POST['attribute']??0);$weap=(int)($_POST['weapon']??0);$itname=trim($_POST['itemname']??'');
    if(strlen($itname)>150||!gw_lookup_exists($con,'listrarity','rareid',$rarity)||!gw_lookup_exists($con,'listreq','req',$req)||!gw_lookup_exists($con,'listattribute','weapattrid',$attrib)||!gw_lookup_exists($con,'listtype','weaponid',$weap)){http_response_code(400);exit('Invalid weapon data.');}
    $stmt=$con->prepare('INSERT INTO history (historydate,userid,charnameid,locationid,goldrec,drop_type,itemreq,itemtype,itemattribute,itemrarity,itemname) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
    $stmt->bind_param('siiiiiiiiis',$treasdate,$uid,$toonid,$locid,$gold,$droptype,$req,$weap,$attrib,$rarity,$itname);
}elseif($droptype===2){
    $matid=(int)($_POST['rarematerial']??0);
    if(!gw_lookup_exists($con,'materials','materialid',$matid)){http_response_code(400);exit('Invalid material.');}
    $stmt=$con->prepare('INSERT INTO history (historydate,userid,charnameid,locationid,goldrec,drop_type,material) VALUES (?,?,?,?,?,?,?)');
    $stmt->bind_param('siiiiii',$treasdate,$uid,$toonid,$locid,$gold,$droptype,$matid);
}elseif($droptype===3){
    $runeid=(int)($_POST['rune']??0);$runerare=(int)($_POST['runerarity']??0);
    if(!gw_lookup_exists($con,'listrunes','runeid',$runeid)||!gw_lookup_exists($con,'listrarity','rareid',$runerare)){http_response_code(400);exit('Invalid rune data.');}
    $stmt=$con->prepare('INSERT INTO history (historydate,userid,charnameid,locationid,goldrec,drop_type,itemrarity,runetype) VALUES (?,?,?,?,?,?,?,?)');
    $stmt->bind_param('siiiiiii',$treasdate,$uid,$toonid,$locid,$gold,$droptype,$runerare,$runeid);
}else{
    $itname='Nothing dropped!';
    $stmt=$con->prepare('INSERT INTO history (historydate,userid,charnameid,locationid,goldrec,drop_type,itemname) VALUES (?,?,?,?,?,?,?)');
    $stmt->bind_param('siiiiis',$treasdate,$uid,$toonid,$locid,$gold,$droptype,$itname);
}
if(!$stmt->execute()){error_log('Insert failed: '.$stmt->error);http_response_code(500);exit('Failed to record drop data.');}
$stmt->close();$con->close();
header('Location: gw-toon.php');
exit;
