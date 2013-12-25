<?php

/*
 * This file is part of the XabbuhCrApiBundle package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\CrApiBundle\DependencyInjection;

use PHPCRAPI\API\RepositoryLoader;
use PHPCRAPI\PHPCR\Collection\RepositoryCollection;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * Dependency Injection extension for the PHPCR API.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class XabbuhCrApiExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        if ($config['repositories']) {
            $container->setParameter('xabbuh_cr_api.repositories', $config['repositories']);
            $loader->load('repository_loader.xml');

            /** @var RepositoryLoader $repositoryLoader */
            $repositoryLoader = $container->get('xabbuh_cr_api.repository_loader');

            $this->registerRepositories($container, $repositoryLoader->getRepositories());
        }
    }

    /**
     * Registers the configured repositories as services in the container.
     *
     * @param ContainerBuilder     $container    The container builder
     * @param RepositoryCollection $repositories The PHPCR repository collection
     */
    private function registerRepositories(
        ContainerBuilder $container,
        RepositoryCollection $repositories
    ) {
        foreach ($repositories->getAll() as $name => $repository) {
            $definition = new Definition(get_class($repository));
            $container->setDefinition($this->getRepositoryId($name), $definition);
        }
    }

    /**
     * Returns a container id for a repository based on its internal name.
     *
     * @param string $name The repository's name
     * @return string The container id
     */
    private function getRepositoryId($name)
    {
        return 'xabbuh_cr_api.repository.'.strtr(strtolower($name), ' ', '_');
    }

    /**
     * {@inheritDoc}
     */
    public function getNamespace()
    {
        return 'http://xabbuh.de/schema/dic/xabbuh/crapi';
    }

    /**
     * {@inheritDoc}
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/schema';
    }
}
 