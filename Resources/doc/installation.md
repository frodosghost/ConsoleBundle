# Install Manhattan Console Bundle

## How

1. Add this bundle to the composer file:

        {
            "require": {
                ...
                "manhattan/console-bundle": "dev-master",
                // Other bundles
                "liip/imagine-bundle": "dev-master",
                "atom/logger-bundle": "1.0.*",
                "mopa/bootstrap-bundle": "3.*@dev",
                "knplabs/knp-components": "1.2.2",
                "knplabs/knp-menu-bundle": "2.0.*@dev",
                "knplabs/knp-paginator-bundle": "2.4.*@dev",
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
