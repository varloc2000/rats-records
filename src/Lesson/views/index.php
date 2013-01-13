<?php

use Core\Database\Connector;

if ($id) {
    $lookupFilename = __DIR__."/$id.html";
    $file = file_exists($lookupFilename) ? $lookupFilename : __DIR__.'/404.html';
    include $file;
} else {
    require './main.php';
}
?>
