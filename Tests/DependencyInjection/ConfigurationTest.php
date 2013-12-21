<?php

/*
 * This file is part of the XabbuhCrApiBundle package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\CrApiBundle\Tests\DependencyInjection;

use Symfony\Component\Config\Definition\ArrayNode;
use Symfony\Component\Config\Definition\PrototypedArrayNode;
use Symfony\Component\Config\Definition\ScalarNode;
use Xabbuh\CrApiBundle\DependencyInjection\Configuration;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ArrayNode
     */
    private $configTree;

    protected function setUp()
    {
        $configuration = new Configuration();
        $this->configTree = $configuration->getConfigTreeBuilder()->buildTree();
    }

    public function testRootNode()
    {
        $this->assertEquals('xabbuh_cr_api', $this->configTree->getName());
        $this->assertInstanceOf(
            'Symfony\Component\Config\Definition\ArrayNode',
            $this->configTree
        );
        $this->assertFalse($this->configTree->isRequired());
    }

    public function testRepositoriesNode()
    {
        $repositoriesNode = $this->getRepositoriesNode();

        $this->assertEquals('repositories', $repositoriesNode->getName());
        $this->assertInstanceOf(
            'Symfony\Component\Config\Definition\PrototypedArrayNode',
            $repositoriesNode
        );
        $this->assertFalse($repositoriesNode->isRequired());

        $this->assertInstanceOf(
            'Symfony\Component\Config\Definition\ArrayNode',
            $repositoriesNode->getPrototype()
        );
    }

    public function testFactoryNode()
    {
        $factoryNode = $this->getFactoryNode();

        $this->assertEquals('factory', $factoryNode->getName());
        $this->assertInstanceOf(
            'Symfony\Component\Config\Definition\ScalarNode',
            $factoryNode
        );
        $this->assertTrue($factoryNode->isRequired());
    }

    public function testParametersNode()
    {
        $parametersNode = $this->getParametersNode();

        $this->assertEquals('parameters', $parametersNode->getName());
        $this->assertInstanceOf(
            'Symfony\Component\Config\Definition\PrototypedArrayNode',
            $parametersNode
        );
        $this->assertFalse($parametersNode->isRequired());

        $this->assertInstanceOf(
            'Symfony\Component\Config\Definition\ScalarNode',
            $parametersNode->getPrototype()
        );
        $this->assertFalse($parametersNode->getPrototype()->isRequired());
    }

    /**
     * @return PrototypedArrayNode
     */
    private function getRepositoriesNode()
    {
        $children = $this->configTree->getChildren();
        return $children['repositories'];
    }

    /**
     * @return ArrayNode
     */
    private function getPrototypeNode()
    {
        return $this->getRepositoriesNode()->getPrototype();
    }

    /**
     * @return ScalarNode
     */
    private function getFactoryNode()
    {
        $children = $this->getPrototypeNode()->getChildren();
        return $children['factory'];
    }

    /**
     * @return PrototypedArrayNode
     */
    private function getParametersNode()
    {
        $children = $this->getPrototypeNode()->getChildren();
        return $children['parameters'];
    }
}
