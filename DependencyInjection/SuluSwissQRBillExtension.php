<?php

declare(strict_types=1);
namespace Linderp\SuluSwissQRBillBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SuluSwissQRBillExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $config = $this->processConfiguration(new Configuration(), $configs);

        $keys = [
            'iban',
            'name',
            'street',
            'buildingNumber',
            'postalCode',
            'city',
            'country',
        ];

        foreach ($keys as $key) {
            $container->setParameter(
                Configuration::ROOT . '.' . $key,
                $config[$key]
            );
        }
        $loader->load('services.yaml');
    }
}
