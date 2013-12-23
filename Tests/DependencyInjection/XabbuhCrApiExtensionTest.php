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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Xabbuh\CrApiBundle\DependencyInjection\XabbuhCrApiExtension;

class XabbuhCrApiExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var XabbuhCrApiExtension
     */
    private $extension;

    /**
     * @var ContainerBuilder
     */
    private $container;

    protected function setUp()
    {
        $this->extension = new XabbuhCrApiExtension();
        $this->container = new ContainerBuilder();
    }

    public function testConfiguration()
    {
        $configs = array(
            'xabbuh_cr_api' => array(
                'repositories' => array(
                    'testrepo' => array(
                        'factory' => 'jackalope.jackrabbit',
                        'parameters' => array(
                            'jackalope.jackrabbit_uri' => 'http://localhost:8080/server',
                            'credentials.username'     => 'foobar',
                            'credentials.password'     => 'foobar',
                        ),
                    ),
                ),
            ),
        );
        $this->extension->load($configs, $this->container);

        $this->container->has('xabbuh_cr_api.repository.testrepo');
    }

    public function testConfigurationWithoutFactory()
    {
        $this->setExpectedException(
            'Symfony\Component\Config\Definition\Exception\InvalidConfigurationException'
        );
        $configs = array(
            'xabbuh_cr_api' => array(
                'repositories' => array(
                    'testrepo' => array(
                        'parameters' => array(
                            'jackalope.jackrabbit_uri' => 'http://localhost:8080/server',
                            'credentials.username'     => 'foobar',
                            'credentials.password'     => 'foobar',
                        ),
                    ),
                ),
            ),
        );
        $this->extension->load($configs, $this->container);
    }

    public function testConfigurationWithSpaceInRepoName()
    {
        $configs = array(
            'xabbuh_cr_api' => array(
                'repositories' => array(
                    'test repo' => array(
                        'factory' => 'jackalope.jackrabbit',
                        'parameters' => array(
                            'jackalope.jackrabbit_uri' => 'http://localhost:8080/server',
                            'credentials.username'     => 'foobar',
                            'credentials.password'     => 'foobar',
                        ),
                    ),
                ),
            ),
        );
        $this->extension->load($configs, $this->container);

        $this->container->has('xabbuh_cr_api.repository.test_repo');
    }

    public function testRepositoryLoaderService()
    {
        $configs = array(
            'xabbuh_cr_api' => array(
                'repositories' => array(
                    'testrepo' => array(
                        'factory' => 'jackalope.jackrabbit',
                        'parameters' => array(
                            'jackalope.jackrabbit_uri' => 'http://localhost:8080/server',
                            'credentials.username'     => 'foobar',
                            'credentials.password'     => 'foobar',
                        ),
                    ),
                ),
            ),
        );
        $this->extension->load($configs, $this->container);

        $this->assertTrue($this->container->has('xabbuh_cr_api.repository_loader'));
    }
}
 