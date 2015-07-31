# Using Manhattan Bundles

Manhattan Console Bundle allows other Symfony2 bundles to interact with the pages. With this, you can create a backend management system that allow Users to edit the data that can be visible on the client side.

We have built, and use in production, bundles that enable web sites to be built quickly, allowing us to focus on the other functionality of the sites, and not having to add new code from older solutions.

## Manhattan Content Bundle

The Manhattan Content Bundle has been used for static pages, of pages that the client would like to update from the Console.

        {
            "require": {
                ...
                "manhattan/console-bundle": "dev-master"
                "manhattan/content-bundle": "dev-master"
                ...
            }
        }

## Manhattan Posts Bundle

Manhattan Posts Bundle adds functionality for handling Posts for frequent content. News and Blogs are great contenders for usage here.

        {
            "require": {
                ...
                "manhattan/console-bundle": "dev-master"
                "manhattan/posts-bundle": "dev-master"
                ...
            }
        }

## Basic Site Setup

With the Manhattan Console, and a few additional installed bundles you can get the backend of a website up and running with the following bundles.

        {
            "require": {
                ...
                "manhattan/console-bundle": "dev-master",
                "manhattan/publish-bundle": "dev-master",
                "manhattan/public-bundle": "dev-master",
                "manhattan/posts-bundle": "dev-master",
                ...
            }
        }
