<?php
session_start();
session_unset();
session_destroy();
header("Location: /autoease/admin/login.php");
exit;
?>
