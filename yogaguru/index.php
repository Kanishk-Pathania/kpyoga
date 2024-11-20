<?php
// index.php
$files = scandir('.');
foreach ($files as $file) {
    echo "<p>$file</p>";
}
?>
