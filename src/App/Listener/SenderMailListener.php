<?php


namespace Sumara\SenderMail\App\Listener;


use PhpAmqpLib\Connection\AMQPStreamConnection;

class SenderMailListener
{
    private $connectionAMQP;
    private $channelAMQP;
    private $nameQueue;

    public function __construct()
    {
        $this->connectionAMQP = new AMQPStreamConnection(getenv('HOST_AMQP'), getenv('PORT_AMQP'), getenv('USER_AMQP'), getenv('PASS_AMQP'));
        $this->channelAMQP = $this->connectionAMQP->channel();
    }

    /**
     * @return mixed
     */
    public function getNameQueue()
    {
        return $this->nameQueue;
    }

    /**
     * @param mixed $nameQueue
     */
    public function setNameQueue($nameQueue)
    {
        $this->nameQueue = $nameQueue;
    }

    public function run(){
        echo 'Running listener';

        $envNameQueue = getenv('NAME_QUEUE');
        if($this->getNameQueue()){
            $envNameQueue = $this->getNameQueue();
        }
        $callbackMessage = function($msg) {

        };
        $this->channelAMQP->queue_declare($envNameQueue, false, false ,false, false);
        $this->channelAMQP->basic_consume($envNameQueue, '', false, true, false, false, $callbackMessage);

        while (count($this->channelAMQP->callbacks)){
            try {
                $this->channelAMQP->wait();
            } catch (\ErrorException $e) {
                throw new \Exception("We found a some problem when AMQP is awaiting", $e->getMessage());
            }
        }

        $this->channelAMQP->close();
        try {
            $this->connectionAMQP->close();
        } catch (\Exception $e) {
        }
    }
}