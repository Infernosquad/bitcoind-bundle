<?php

namespace Infernosquad\BitcoindBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
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

        foreach ($config['drivers'] as $name => $driver)
        {
            $definition = new Definition($driver['class']);

            foreach ($driver['options'] as $key => $value)
            {
                $definition->addMethodCall('addCurlOption',[$key,$value]);
            }

            $container->setDefinition(sprintf('infernosquad.bitcoind.drivers.%s',$name),$definition);
        }

        foreach ($config['clients'] as $name => $client)
        {
            $definition = new Definition('Nbobtc\Http\Client',[$client['dsn']]);

            if(isset($client['driver'])){
                $definition->addMethodCall('withDriver',[new Reference(sprintf('infernosquad.bitcoind.drivers.%s',$client['driver']))]);
            }

            $container->setDefinition(sprintf('infernosquad.bitcoind.clients.%s',$name),$definition);
        }
    }
}
