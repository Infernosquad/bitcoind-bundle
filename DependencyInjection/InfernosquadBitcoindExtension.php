<?php

namespace Infernosquad\BitcoindBundle\DependencyInjection;

use Nbobtc\Http\Client;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class InfernosquadBitcoindExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config['clients'] as $name => $client)
        {
            $definition = new Definition('Nbobtc\Http\Client',[$client['dsn']]);

            $driver = new $client['driver']['class'];

            foreach ($client['driver']['options'] as $key => $value)
            {
                $driver->addCurlOption($key,$value);
            }

            $definition->setMethodCalls('withDriver',$driver);

            $container->setDefinition(sprintf('infernosquad.bitcoind.%s',$name),$definition);
        }
    }
}
