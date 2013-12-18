Usage
=====

1. Install XabbuhCrApiBundle using Composer

    Add ``xabbuh/cr-api-bundle`` and its dependencies to the ``require`` section
    of your project's ``composer.json``:

    ```json
    {
        "require": {
            "jackalope/jackalope-jackrabbit": "~1.1@dev",
            "marmelab/phpcr-api": "dev-master",
            "xabbuh/cr-api-bundle": "dev-master@dev"
        }
    }
    ```

    Install them with Composer:

        php composer.phar install

    After Composer has installed XabbuhCrApiBundle you will find it in your
    project's ``vendor/xabbuh/cr-api-bundle`` directory.

2. Enable the bundle

    Register the bundle in the kernel:

    ```php
    // app/AppKernel.php

    // ...
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Xabbuh\CrApiBundle\XabbuhCrApiBundle(),
        );

        return $bundles;
    }
    ```

3. Configure your repositories

    XabbuhCrApiBundle registers a service for every bundle that you configure.
    A simple configuration can look like this:

    ```yaml
    xabbuh_cr_api:
        repositories:
            "Repository Test":
                factory:    "jackalope.jackrabbit"
                parameters:
                    jackalope.jackrabbit_uri: http://localhost:8080/server
                    credentials.username:     admin
                    credentials.password:     admin
    ```

    Read the [PHPCR API documentation](https://github.com/marmelab/phpcr-api/blob/master/README.md)
    to learn which PHPCR factories are supported by the PHPCR API library and
    which parameters can be to configure each of them.

    You can now access your repository via the service container (note that the
    repository loader transforms repository names to lower-case and replaces
    spaces with tabs):

    ```php
    $repository = $container->get('xabbuh_cr_api.repository.repository_test');
    ```
