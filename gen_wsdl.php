<?php
require "vendor/autoload.php";
// include the class we want to use
require "Events.php";
$gen = new \PHP2WSDL\PHPClass2WSDL("Events", "http://localhost/path/to/project/root/Server.php");
$gen->generateWSDL();
file_put_contents("wsdl", $gen->dump());