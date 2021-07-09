<?php 

namespace Redis\Contract;

/**
 * @since 1.0.0
 */
interface ConnectorInterface
{
    /**
     * @param array $config
     * @param array $option
     *
     * @return Object
     */
    public function connect(array $config, array $option);

    /**
     * @param array $config
     * @param array $option
     *
     * @return Object
     */
    public function connectToCluster(array $config, array $option);
}
