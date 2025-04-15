<?php
session_start();
session_unset(); // remove all session variables
session_destroy(); // end session

header('Location: ?page=login');
exit;