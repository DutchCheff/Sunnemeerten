<?php

// Connect to the database
try {
   $conn = new mysqli("localhost", "root", "", "my_content_db");
   $hasfaild = false;
} catch (\Throwable $th) {
    //throw $th;
    echo "<p> Database connection failed </p>";
    $hasfaild = true;
    $_SESSION['user_id'] = "failed";
}

?>