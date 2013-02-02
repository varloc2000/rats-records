<?php
    $a = '';
    foreach ($lessons as $lesson) {
        $a .= $lesson['title'];
    }
    return $a;
?>
