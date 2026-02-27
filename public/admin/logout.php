<?php
session_start();
session_destroy();
header("Location: /programa-admin/public/admin/login.php");
exit;
?>