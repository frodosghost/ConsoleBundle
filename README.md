# Manhattan Console Bundle

## What
ConsoleBundle is application setup for ease of starting new project in Symfony2.

## How
1. Add this bundle to the composer file:

        {
            "require": {
                ...
                "manhattan/console-bundle": "dev-master"
            }
        }

2. Add this bundle to your app kernel:

        // app/AppKernel.php
        public function registerBundles()
        {
            return array(
                // ...
                new Manhattan\Bundle\ConsoleBundle\ManhattanConsoleBundle(),
                new Ornj\Bundle\MarkdownBundle\OrnjMarkdownBundle(),
                new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
                new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),
                new Knp\Bundle\MenuBundle\KnpMenuBundle(),
                new FOS\UserBundle\FOSUserBundle(),
                // ...
            );
        }

3. Add the routing to the `config.yml` routing

        _console_bundle:
        resource: "@ManhattanConsoleBundle/Resources/config/routing.yml"

4. Under the `imports:` line in `app/config.yml` put the security file for the bundle before the `security.yml` line. This will allow application overloading of the bundle security file:

        - { resource: '@ManhattanConsoleBundle/Resources/config/security.yml' }
        - { resource: security.yml }
