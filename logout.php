<?php
setcookie("KlinoffUsername", "", time() - 3600, "/");
header("Location: index.php");
?>