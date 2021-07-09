<?php

namespace Redis\Connectors;

use Illuminate\Contracts\Redis\Connector;
use Illuminate\Redis\Connections\PredisClusterConnection;
use Illuminate\Redis\Connections\PredisConnection;
use Illuminate\Support\Arr;
use Predis\Client;

class PredisConnector implements Connector
{
    
    public function connect(array $config, array $options)
    {
       
    }

   
    public function connectToCluster(array $config, array $clusterOptions, array $options)
    {
       
    }
}
