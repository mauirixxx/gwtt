<?php
session_start();
require_once 'gw-connect.php';

// 1. Strict Authentication Check
if (!isset($_SESSION['userid']) \vert{}\vert{} empty($_SESSION['userid'])) {
    header('Location: gw-login.php');
    exit;
}

// 2. Database Connection
$con = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
if ($con->connect_errno) {
    error_log("Database connection failed: " . $con->connect_error);
    die("A database error occurred.");
}

$userid = (int)$_SESSION['userid'];
$createnew =$_POST['docreate'] ?? '';
$errorMsg = '';$successMsg = '';

function getColorArray() {
    return ["#FFF", "#DDD", "#FF8", "#CF9", "#ACF", "#9FC", "#DAF", "#FBB", "#FCE", "#BFF", "#FC9", "#DDF"];
}

// 3. Process Form Submission
if ($createnew === "1") {
    $cname = trim($_POST['cname'] ?? '');
    $bdate = trim($_POST['bdate'] ?? '');
    $profidRaw =$_POST['professionid'] ?? '';

    // Validate Profession ID
    if ($profidRaw === '' || !is_numeric($profidRaw)) {$errorMsg = 'Please choose a valid profession.';
    } else {
        $profid = (int)$profidRaw;
        $colors = getColorArray();$profcolor = $colors[$profid] ?? '#FFFFFF'; // Bounds checking with safe fallback
    }

    // Validate Character Name
    if (empty($errorMsg) && empty($cname)) {$errorMsg = 'Please enter a name for your character.';
    }

    // Validate Birthdate
    if (empty($errorMsg)) {
        $dateParts = explode('-',$bdate);
        if (count($dateParts) !== 3 || !checkdate((int)$dateParts[1], (int)$dateParts[2], (int)$dateParts[0])) {$errorMsg = 'Date is invalid (' . htmlspecialchars($bdate, ENT_QUOTES, 'UTF-8') . '). Format must be YYYY-MM-DD.';
        }
    }

    // Insert Record via Prepared Statement
    if (empty($errorMsg)) {
        $stmtInsert =$con->prepare("INSERT INTO `playername` (charname, birthdate, userid, professionid, profcolor) VALUES (?, ?, ?, ?, ?)");
        $stmtInsert->bind_param("ssiis", $cname, $bdate,$userid, $profid,$profcolor);

        if ($stmtInsert->execute()) {
            $stmtInsert->close();$con->close();
            header("refresh:3;url=gw-toon.php");
            $successMsg = "Character created successfully! Redirecting...";
        } else {
            error_log("Insert error: " . $stmtInsert->error);$errorMsg = "An error occurred while creating your character.";
            $stmtInsert->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="gw-style.css">
    <title>Character Creation</title>
</head>
<body>
<div style="text-align: center; margin-top: 30px;">

<?php if (!empty($successMsg)): ?>
    <p><?php echo htmlspecialchars($successMsg, ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Returning to character selection in 3 seconds...</p>
<?php else: ?>

    <?php if (!empty($errorMsg)): ?>
        <p style="color: red;"><?php echo $errorMsg; ?></p>
        <p><a href="gw-create.php" class="navlink">Click HERE to try again</a></p>
    <?php else: ?>

        <!-- CREATION FORM -->
        <form method="POST" action="gw-create.php">
            <input type="hidden" name="docreate" value="1">
            
            <label for="cname">Character name:</label>
            <input type="text" id="cname" name="cname" maxlength="19" size="20" required><br /><br />
            
            <label for="bdate">Birthdate:</label>
            <input id="bdate" name="bdate" type="date" placeholder="2005-04-28" required><br /><br />
            
            <label for="professionid">Profession:</label>
            <select id="professionid" name="professionid" required>
                <option selected disabled value="">Choose Profession</option>
                <?php
                $sqlprofession = "SELECT runeprofid, runeprofession FROM (SELECT runeprofid, runeprofession FROM listruneprofessions ORDER BY runeprofid DESC LIMIT 10) sub ORDER BY runeprofid ASC";
                if ($stmtProf =$con->prepare($sqlprofession)) {$stmtProf->execute();
                    $resProf =$stmtProf->get_result();
                    while ($row =$resProf->fetch_assoc()) {
                        echo '<option value="' . (int)$row['runeprofid'] . '">' . htmlspecialchars($row['runeprofession'], ENT_QUOTES, 'UTF-8') . '</option>';
                    }
                    $stmtProf->close();
                }
                ?>
            </select><br /><br />
            
            <input type="submit" value="Create character ...">
        </form>

    <?php endif; ?>

<?php endif; ?>

    <br /><br />
    <p>Return to <a href="gw-index.php" class="navlink">home</a>.</p>

    <br /><br />
    <form method="POST" action="gw-logout.php">
        <input type="hidden" name="logout" value="1">
        <input type="submit" value="Logout">
    </form>

</div>
</body>
</html>
<?php $con->close(); ?>
