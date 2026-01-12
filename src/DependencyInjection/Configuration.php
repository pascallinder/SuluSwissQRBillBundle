<?php

declare(strict_types=1);

namespace Linderp\SuluSwissQRBillBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const ROOT = 'sulu_swiss_qr_bill';
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::ROOT);

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->scalarNode('iban')->end()
                ->scalarNode('name')->end()
                ->scalarNode('street')->end()
                ->scalarNode('buildingNumber')->defaultNull()->end()
                ->integerNode('postalCode')->end()
                ->scalarNode('city')->end()
                ->scalarNode('country')->defaultValue('CH')->end();

        return $treeBuilder;
    }
}
