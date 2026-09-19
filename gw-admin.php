<?php
session_start();
require_once 'gw-connect.php';
require_once 'gw-security.php';

if (empty($_SESSION['userid'])) { header('Location: gw-index.php'); exit; }
if ((int)($_SESSION['access'] ?? 0) !== 9) { http_response_code(403); exit('Access denied.'); }

$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
if ($con->connect_errno) { error_log('Database error: '.$con->connect_error); exit('A database error occurred.'); }
$con->set_charset('utf8mb4');

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    gw_require_csrf();
    $action = $_POST['admin_action'] ?? '';

    if ($action === 'delete_character') {
        $playerId = (int)($_POST['playerid'] ?? 0);
        $stmt = $con->prepare('SELECT charname FROM playername WHERE playerid = ?');
        $stmt->bind_param('i', $playerId);
        $stmt->execute();
        $character = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$character) {
            $error = 'Character not found.';
        } else {
            $con->begin_transaction();
            try {
                $stmt = $con->prepare('DELETE FROM playername WHERE playerid = ?');
                $stmt->bind_param('i', $playerId);
                if (!$stmt->execute()) { throw new RuntimeException($stmt->error); }
                $stmt->close();
                $con->commit();
                if ((int)($_SESSION['playerid'] ?? 0) === $playerId) {
                    unset($_SESSION['playerid'], $_SESSION['profcolor']);
                }
                $message = 'Deleted character "' . $character['charname'] . '" and all associated loot history.';
            } catch (Throwable $e) {
                $con->rollback();
                error_log('Admin character delete failed: '.$e->getMessage());
                $error = 'The character could not be deleted.';
            }
        }
    } elseif ($action === 'delete_history') {
        $historyId = (int)($_POST['historyid'] ?? 0);
        $stmt = $con->prepare('DELETE FROM history WHERE historyid = ?');
        $stmt->bind_param('i', $historyId);
        if ($stmt->execute() && $stmt->affected_rows === 1) $message = 'Loot entry deleted.';
        else $error = 'Loot entry not found or could not be deleted.';
        $stmt->close();
    } elseif ($action === 'update_history') {
        $historyId = (int)($_POST['historyid'] ?? 0);
        $historyDate = trim($_POST['historydate'] ?? '');
        $gold = (int)($_POST['goldrec'] ?? -1);
        $itemName = trim($_POST['itemname'] ?? '');
        if (!gw_valid_date($historyDate)) $error = 'Please enter a valid date.';
        elseif ($gold < 0 || $gold > 16777215) $error = 'Gold amount is invalid.';
        elseif (strlen($itemName) > 150) $error = 'Item name is too long.';
        else {
            $stmt = $con->prepare('UPDATE history SET historydate = ?, goldrec = ?, itemname = ? WHERE historyid = ?');
            $stmt->bind_param('sisi', $historyDate, $gold, $itemName, $historyId);
            if ($stmt->execute() && $stmt->affected_rows >= 0) $message = 'Loot entry updated.';
            else $error = 'Loot entry could not be updated.';
            $stmt->close();
        }
    }
}

$selectedPlayer = (int)($_GET['playerid'] ?? $_POST['filter_playerid'] ?? 0);
$characters = [];
$r = $con->query('SELECT p.playerid,p.charname,p.birthdate,rp.runeprofession AS profession,(SELECT COUNT(*) FROM history h WHERE h.charnameid=p.playerid) AS history_count FROM playername p LEFT JOIN listruneprofessions rp ON p.professionid=rp.runeprofid ORDER BY p.charname');
while ($row = $r->fetch_assoc()) $characters[] = $row;
$r->close();

$history = [];
if ($selectedPlayer > 0) {
    $stmt = $con->prepare("SELECT h.historyid,h.historydate,h.goldrec,h.drop_type,h.itemname,p.charname,t.location,
        CASE h.drop_type WHEN 1 THEN CONCAT(COALESCE(lrat.rarity,''),' r',COALESCE(h.itemreq,''),' ',COALESCE(la.weaponattribute,''),' ',COALESCE(lt.weapontype,''),' ',COALESCE(h.itemname,''))
        WHEN 2 THEN COALESCE(m.material,'Material')
        WHEN 3 THEN CONCAT(COALESCE(lrat.rarity,''),' rune of ',COALESCE(lr.runes,'Unknown'))
        WHEN 4 THEN 'Nothing dropped' ELSE 'Unknown' END AS drop_summary
        FROM history h JOIN playername p ON h.charnameid=p.playerid JOIN treasuredata t ON h.locationid=t.treasureid
        LEFT JOIN listrarity lrat ON h.itemrarity=lrat.rareid LEFT JOIN listattribute la ON h.itemattribute=la.weapattrid
        LEFT JOIN listtype lt ON h.itemtype=lt.weaponid LEFT JOIN materials m ON h.material=m.materialid LEFT JOIN listrunes lr ON h.runetype=lr.runeid
        WHERE h.charnameid=? ORDER BY h.historydate DESC,h.historyid DESC");
    $stmt->bind_param('i',$selectedPlayer); $stmt->execute(); $res=$stmt->get_result();
    while($row=$res->fetch_assoc()) $history[]=$row;
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><link rel="stylesheet" type="text/css" href="gw-style.css"><title>Admin Dashboard</title>
<style>
.admin-wrap{max-width:1100px;margin:30px auto;padding:0 20px}.admin-table{width:100%;border-collapse:collapse;background:rgba(255,255,255,.82)}.admin-table th,.admin-table td{padding:9px;border:1px solid #aaa;text-align:left}.admin-table form{margin:0}.admin-actions{display:flex;gap:6px;align-items:center;flex-wrap:wrap}.admin-danger{background:#8b1e1e;color:#fff;border:0;padding:7px 10px;cursor:pointer}.admin-message{padding:10px;background:#e8f5e9}.admin-error{padding:10px;background:#ffebee}
</style></head><body>
<?php require 'gw-header.php'; ?>
<main class="admin-wrap">
<h2>Administrator Tools</h2>
<?php if($message): ?><p class="admin-message"><?php echo htmlspecialchars($message,ENT_QUOTES,'UTF-8'); ?></p><?php endif; ?>
<?php if($error): ?><p class="admin-error"><?php echo htmlspecialchars($error,ENT_QUOTES,'UTF-8'); ?></p><?php endif; ?>

<h3>Characters</h3>
<table class="admin-table"><thead><tr><th>Character</th><th>Profession</th><th>Birthdate</th><th>Loot entries</th><th>Actions</th></tr></thead><tbody>
<?php foreach($characters as $c): ?><tr>
<td><?php echo htmlspecialchars($c['charname'],ENT_QUOTES,'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($c['profession']??'',ENT_QUOTES,'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($c['birthdate']??'',ENT_QUOTES,'UTF-8'); ?></td>
<td><?php echo (int)$c['history_count']; ?></td>
<td class="admin-actions"><a class="navlink" href="gw-admin.php?playerid=<?php echo (int)$c['playerid']; ?>">Manage loot</a>
<form method="POST" onsubmit="return confirm('Delete this character and ALL of their loot history? This cannot be undone.');"><?php echo gw_csrf_input(); ?><input type="hidden" name="admin_action" value="delete_character"><input type="hidden" name="playerid" value="<?php echo (int)$c['playerid']; ?>"><button class="admin-danger" type="submit">Delete character</button></form></td>
</tr><?php endforeach; ?></tbody></table>

<?php if($selectedPlayer>0): ?>
<h3>Loot entries</h3>
<?php if($history): ?>
<table class="admin-table"><thead><tr><th>Date</th><th>Character</th><th>Location / Drop</th><th>Gold</th><th>Item name</th><th>Actions</th></tr></thead><tbody>
<?php foreach($history as $h): ?><tr>
<form method="POST">
<td><input type="date" name="historydate" value="<?php echo htmlspecialchars($h['historydate'],ENT_QUOTES,'UTF-8'); ?>" required></td>
<td><?php echo htmlspecialchars($h['charname'],ENT_QUOTES,'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($h['location'].' — '.$h['drop_summary'],ENT_QUOTES,'UTF-8'); ?></td>
<td><input type="number" name="goldrec" min="0" max="16777215" value="<?php echo (int)$h['goldrec']; ?>" required></td>
<td><input type="text" name="itemname" maxlength="150" value="<?php echo htmlspecialchars($h['itemname']??'',ENT_QUOTES,'UTF-8'); ?>"></td>
<td class="admin-actions"><?php echo gw_csrf_input(); ?><input type="hidden" name="historyid" value="<?php echo (int)$h['historyid']; ?>"><input type="hidden" name="filter_playerid" value="<?php echo $selectedPlayer; ?>"><button type="submit" name="admin_action" value="update_history">Save</button><button class="admin-danger" type="submit" name="admin_action" value="delete_history" onclick="return confirm('Delete this loot entry? This cannot be undone.');">Delete</button></td>
</form></tr><?php endforeach; ?></tbody></table>
<?php else: ?><p>No loot entries for this character.</p><?php endif; ?>
<?php endif; ?>
</main></body></html>
<?php $con->close(); ?>
