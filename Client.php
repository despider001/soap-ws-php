<?php
try {
    $client = new SoapClient("http://localhost/path/to/project/root/Server.php?wsdl");
    $events = $client->getEvents();
    print_r($events);
} catch (SoapFault $e) {
    var_dump($e);
}
