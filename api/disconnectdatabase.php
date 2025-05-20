<?php
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>