# Manhattan Console Bundle

## What
ConsoleBundle is application setup for ease of starting new project in Symfony2. It intergrates the [MopaBootstrapBundle](https://github.com/phiamo/MopaBootstrapBundle) and is used as a basis for a client control system for the backend of a website.

## How
1. Add this bundle to the deps file:

        [ManhattanConsoleBundle]
        git=git://github.com:frodosghost/ConsoleBundle.git
        target=/bundles/Manhattan/Bundle/ConsoleBundle

2. Add the `ConsoleBundle` class to your project's autoloader:

        // app/autoload.php
        $loader->registerPrefixes(array(
            'Manhattan'        => __DIR__.'/../vendor/bundles',
        ));

3. Add this bundle to your app kernel:

        // app/AppKernel.php
        public function registerBundles()
        {
            return array(
                // ...
                new Manhattan\Bundle\ConsoleBundle\ManhattanConsoleBundle(),
                // ...
            );
        }

4. Add the routing to the `config.yml` routing

        _console_bundle:
        resource: "@ManhattanConsoleBundle/Resources/config/routing.yml"

5. Under the `imports:` line in `app/config.yml` put the security file for the bundle before the `security.yml` line. This will allow application overloading of the bundle security file:

        - { resource: parameters.ini }
        - { resource: '@ManhattanConsoleBundle/Resources/config/security.yml' }
        - { resource: security.yml }
