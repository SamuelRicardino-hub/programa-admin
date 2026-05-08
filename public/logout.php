<?php
session_start();
session_destroy();
header("Location: /programa-admin/index");
exit;
?>