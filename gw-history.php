<?php
session_start();
require_once 'gw-connect.php';
require_once 'gw-security.php';

if(empty($_SESSION['userid'])||empty($_SESSION['playerid'])){header('Location: gw-index.php');exit;}
$con=new mysqli(DATABASE_HOST,DATABASE_USER,DATABASE_PASS,DATABASE_NAME);
if($con->connect_errno){error_log('Database connection failed: '.$con->connect_error);exit('A database error occurred.');}
$con->set_charset('utf8mb4');
$uid=(int)$_SESSION['userid'];$pid=(int)$_SESSION['playerid'];
if(!gw_character_belongs_to_user($con,$pid,$uid)){unset($_SESSION['playerid'],$_SESSION['profcolor']);$con->close();header('Location: gw-toon.php');exit;}
$profcolor=$_SESSION['profcolor']??'#ffffff';if(!preg_match('/^#(?:[a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/',$profcolor))$profcolor='#ffffff';

$sql="SELECT h.historydate,h.goldrec,h.drop_type,h.itemreq,h.itemname,p.charname,t.location,t.wikilink,
lr.runes AS runename,li.insignia AS insignianame,lrat.rarity AS rarityname,la.weaponattribute AS attrname,lt.weapontype AS weapname,m.material AS matname
FROM history h INNER JOIN treasuredata t ON h.locationid=t.treasureid INNER JOIN playername p ON h.charnameid=p.playerid
LEFT JOIN listrunes lr ON h.runetype=lr.runeid LEFT JOIN listinsignias li ON h.insignia=li.insigniaid LEFT JOIN listrarity lrat ON h.itemrarity=lrat.rareid
LEFT JOIN listattribute la ON h.itemattribute=la.weapattrid LEFT JOIN listtype lt ON h.itemtype=lt.weaponid
LEFT JOIN materials m ON h.material=m.materialid WHERE h.charnameid=? AND h.userid=? ORDER BY h.historydate ASC,h.historyid ASC";
$stmt=$con->prepare($sql);$stmt->bind_param('ii',$pid,$uid);$stmt->execute();$result=$stmt->get_result();
$historyRows=[];$totalGold=0;$characterName='';
while($row=$result->fetch_assoc()){
    $historyRows[]=$row;
    $totalGold+=(int)$row['goldrec'];
    if($characterName==='')$characterName=(string)$row['charname'];
}
if($characterName===''){
    $nameStmt=$con->prepare('SELECT charname FROM playername WHERE playerid=? AND userid=?');
    $nameStmt->bind_param('ii',$pid,$uid);$nameStmt->execute();
    $nameRow=$nameStmt->get_result()->fetch_assoc();
    $characterName=$nameRow['charname']??'Character';
    $nameStmt->close();
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><link rel="stylesheet" type="text/css" href="gw-style.css"><title>Treasure Data</title>
<style>
body{background-color:<?php echo htmlspecialchars($profcolor,ENT_QUOTES,'UTF-8'); ?>}
.history-wrap{max-width:1100px;margin:28px auto;padding:0 20px}.history-heading{text-align:center;margin-bottom:22px}.history-heading h2{margin-bottom:6px}.history-total{font-size:1.1rem;font-weight:bold}.history-table{width:100%;border-collapse:collapse;background:rgba(255,255,255,.72)}.history-table th,.history-table td{border:1px solid rgba(0,0,0,.25);padding:10px 12px;text-align:left}.history-table th{background:rgba(255,255,255,.72)}.history-table tbody tr:nth-child(even){background:rgba(255,255,255,.28)}.history-gold{text-align:right!important;white-space:nowrap}.history-date{white-space:nowrap}.history-itemname{font-style:italic}@media(max-width:700px){.history-wrap{overflow-x:auto}.history-table{min-width:650px}}
</style></head><body>
<?php require 'gw-header.php'; ?>
<main class="history-wrap">
<div class="history-heading"><h2>Treasure History — <?php echo htmlspecialchars($characterName,ENT_QUOTES,'UTF-8'); ?></h2>
<div class="history-total">Total Gold Collected: <?php echo number_format($totalGold); ?> GP</div></div>
<?php if($historyRows): ?><table class="history-table"><thead><tr><th>Date</th><th>Location</th><th>Gold</th><th>Reward</th></tr></thead><tbody>
<?php foreach($historyRows as $row): ?><tr>
<td class="history-date"><?php $date=new DateTime($row['historydate']);echo htmlspecialchars($date->format('M j, Y'),ENT_QUOTES,'UTF-8'); ?></td>
<td><a href="<?php echo htmlspecialchars($row['wikilink'],ENT_QUOTES,'UTF-8'); ?>" class="navlink"><?php echo htmlspecialchars($row['location'],ENT_QUOTES,'UTF-8'); ?></a></td>
<td class="history-gold"><?php echo number_format((int)$row['goldrec']); ?> GP</td>
<td><?php if((int)$row['drop_type']===3): ?><?php echo htmlspecialchars($row['rarityname']??'',ENT_QUOTES,'UTF-8'); ?><?php if(!empty($row['runename'])): ?> <?php echo htmlspecialchars($row['runename'],ENT_QUOTES,'UTF-8'); ?> Rune<?php endif; ?><?php if(!empty($row['insignianame'])): ?><?php echo !empty($row['runename'])?' + ':' '; ?><?php echo htmlspecialchars($row['insignianame'],ENT_QUOTES,'UTF-8'); ?><?php endif; ?>
<?php elseif((int)$row['drop_type']===4): ?>Nothing dropped
<?php elseif((int)$row['drop_type']===2): ?><?php echo htmlspecialchars($row['matname']??'Unknown material',ENT_QUOTES,'UTF-8'); ?>
<?php else: ?><?php echo htmlspecialchars($row['rarityname']??'',ENT_QUOTES,'UTF-8'); ?> R<?php echo (int)$row['itemreq']; ?> <?php echo htmlspecialchars($row['attrname']??'',ENT_QUOTES,'UTF-8'); ?> <?php echo htmlspecialchars($row['weapname']??'',ENT_QUOTES,'UTF-8'); ?><?php if(!empty($row['itemname'])): ?> — <span class="history-itemname"><?php echo htmlspecialchars($row['itemname'],ENT_QUOTES,'UTF-8'); ?></span><?php endif; ?>
<?php endif; ?></td></tr><?php endforeach; ?>
</tbody></table><?php else: ?><p style="text-align:center">There is no data to display for that character yet.</p><?php endif; ?>
</main><br>
<div style="text-align:center"><form method="POST" action="gw-toon.php"><?php echo gw_csrf_input(); ?><input type="submit" value="Return to character selection"></form></div></body></html>
<?php $stmt->close();$con->close(); ?>
