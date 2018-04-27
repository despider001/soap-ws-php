# SOAP WebService (Server & Client) Using PHP
---
## Steps

1.  [Define your Methods](#define-your-methods)
2.  [Generate WSDL file for Your Methods](#generate-wSDL-file-for-your-methods)
3.  [Create Server.php to serve your WebService](#create-server.php-to-serve-your-webService)
4.  [Create Client.php to consume/test your WebService](#create-client.php-to-consume/test-your-webService)

### 1. Define your Methods

In our case, we are going to have an Events class, inside Events.php. It has 3 events info in an array. Each event has name, date and location info. We got 2 public methods. getEvents() gives all events and getEventById($event_id) gives a specific event info.

```        <?php
            class Events {
                protected $events = array(
                    1 => array("name" => "Excellent PHP Event",
                        "date" => 1454994000,
                        "location" => "Amsterdam"
                        ),
                    2 => array("name" => "Marvellous PHP Conference",
                        "date" => 1454112000,
                        "location" => "Toronto"),
                    3 => array("name" => "Fantastic Community Meetup",
                        "date" => 1454894800,
                        "location" => "Johannesburg"
                        )
                );
                /**
                * Get all the events we know about
                *
                * @return array The collection of events
                */
                public function getEvents() {
                    return $this->events;
                }
                /**
                * Fetch the detail for a single event
                *
                * @param int $event_id The identifier of the event
                *
                * @return array The event data
                */
                public function getEventById($event_id) {
                    if(isset($this->events[$event_id])) {
                        return $this->events[$event_id];
                    } else {
                        throw new Exception("Event not found");
                    }
                }
            }
```

### 2. Generate WSDL file for Your Methods

1.  Download and install composer (package manager for php) from http://getcomposer.org/
2.  In the project root, open CLI and run `composer require php2wsdl/php2wsdl` This will create the vendor dir in your project root
3.  Now, to create the wsdl file, we need to run the following code. In my case, gen_wsdl.php contains the code.
```
<?php
        require "vendor/autoload.php";
        // include the class we want to use
        require "Events.php";
        $gen = new \PHP2WSDL\PHPClass2WSDL("Events","http://localhost/path/to/project/root/Server.php"); 
        /* don't worry, we will create Server.php soon */
        $gen->generateWSDL();
        file_put_contents("wsdl", $gen->dump());
```
4.  Now run gen_wsdl.php. (http://localhost/path/to/project/root/gen_wsdl.php)

Now the project root should contain the wsdl file.
wsdl file:
```
<?xml version="1.0"?>
    <definitions xmlns="http://schemas.xmlsoap.org/wsdl/" xmlns:tns="http://localhost/path/to/project/root/Server.php" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap-enc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" name="Events" targetNamespace="http://localhost/path/to/project/root/Server.php">
      <types>
        <xsd:schema targetNamespace="http://localhost/path/to/project/root/Server.php">
          <xsd:import namespace="http://schemas.xmlsoap.org/soap/encoding/"/>
        </xsd:schema>
      </types>
      <portType name="EventsPort">
        <operation name="getEvents">
          <documentation>Get all the events we know about</documentation>
          <input message="tns:getEventsIn"/>
          <output message="tns:getEventsOut"/>
        </operation>
        <operation name="getEventById">
          <documentation>Fetch the detail for a single event</documentation>
          <input message="tns:getEventByIdIn"/>
          <output message="tns:getEventByIdOut"/>
        </operation>
      </portType>
      <binding name="EventsBinding" type="tns:EventsPort">
        <soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"/>
        <operation name="getEvents">
          <soap:operation soapAction="http://localhost/path/to/project/root/Server.php#getEvents"/>
          <input>
            <soap:body use="encoded" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://localhost/path/to/project/root/Server.php"/>
          </input>
          <output>
            <soap:body use="encoded" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://localhost/path/to/project/root/Server.php"/>
          </output>
        </operation>
        <operation name="getEventById">
          <soap:operation soapAction="http://localhost/path/to/project/root/Server.php#getEventById"/>
          <input>
            <soap:body use="encoded" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://localhost/path/to/project/root/Server.php"/>
          </input>
          <output>
            <soap:body use="encoded" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://localhost/path/to/project/root/Server.php"/>
          </output>
        </operation>
      </binding>
      <service name="EventsService">
        <port name="EventsPort" binding="tns:EventsBinding">
          <soap:address location="http://localhost/path/to/project/root/Server.php"/>
        </port>
      </service>
      <message name="getEventsIn"/>
      <message name="getEventsOut">
        <part name="return" type="soap-enc:Array"/>
      </message>
      <message name="getEventByIdIn">
        <part name="event_id" type="xsd:int"/>
      </message>
      <message name="getEventByIdOut">
        <part name="return" type="soap-enc:Array"/>
      </message>
    </definitions>
```

### 3. Create Server.php to serve your WebService

```
    <?php
        require('Events.php');
        $server = new SoapServer("wsdl"); // wsdl file name
        $server->setClass('Events');
        $server->handle();
```
Now, the wsdl should be available at  http://localhost/path/to/project/root/Server.php?wsdl

### 4. Create Client.php to consume/test your WebService
```
<?php
    try {
        $client = new SoapClient("http://localhost/path/to/project/root/Server.php?wsdl");
        $events = $client->getEvents();
        print_r($events);
    } catch (SoapFault $e) {
        var_dump($e);
    }

```

As you go to http://localhost/path/to/project/root/Client.php, you should see an array of events.
```
Array ( 
    [1] => Array ( 
            [name] => Excellent PHP Event 
            [date] => 1454994000 
            [location] => Amsterdam ) 
    [2] => Array ( 
            [name] => Marvellous PHP Conference 
            [date] => 1454112000 
            [location] => Toronto ) 
    [3] => Array ( 
            [name] => Fantastic Community Meetup 
            [date] => 1454894800 
            [location] => Johannesburg ) 
    ) 
```