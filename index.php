<?php
require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$listener = new \Sumara\SenderMail\App\Listener\SenderMailListener();
if($argv[1] != null){
    $listener->setNameQueue($argv[1]);
}
$listener->run();