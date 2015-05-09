<?php

namespace Airavata\Client;

class AiravataClientFactory
{

    private $airavataServerHost;
    private $airavataServerPort;

    public function __construct($options)
    {
        $this->airavataServerHost = isset($options['airavataServerHost']) ? $options['airavataServerHost'] : null;
        $this->airavataServerPort = isset($options['airavataServerPort']) ? $options['airavataServerPort'] : null;
    }

    public function getAiravataClient()
    {
        $transport = new TSocket($this->airavataServerHost, $this->airavataServerPort);
        $protocol = new TBinaryProtocol($transport);
	$transport->open();
        return new AiravataClient($protocol);
    }
}
