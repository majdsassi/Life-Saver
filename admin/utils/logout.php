<?php
session_start();
session_unset();
session_destroy();

header("Location: http://localhost/Life-Saver/login.php");
exit();
