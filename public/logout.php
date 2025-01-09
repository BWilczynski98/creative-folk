<?php
declare(strict_types=1);
include "../src/bootstrap.php";

$cms->session()->delete();
redirect("index.php", []);