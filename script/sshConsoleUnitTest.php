<?php
require_once("sshConsole.php");
$console = new sshConsole;
$console->connect("aaron", "c7w2l181", "localhost", 22);
$console->run("ls -l");
echo $console->getOutput() . "\n";
echo $console->getErrorOutput() . "\n";
?>
