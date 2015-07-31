# Configuration

To configure the Console Bundle include the following in the Symfony base `config.yml`.

``` yaml
manhattan_console:
    domain: %domain%         # This is Required. Sets the domain. For example "domain.com" or "domain.dev".

    users:
        from:         no-reply@acmedemo.com         # Default value: ''. Sets the From address when
                                                    # an email is sent from the Console

        console_name: Acme Demo                     # Default value: 'Manhattan'. Sets the Console name as sent
                                                    # in emails. This may differ from the `navigation.title`.

        user_class:   Acme\DemoBundle\Entity\User   # Default value: 'Manhattan\Bundle\ConsoleBundle\Entity\User'.
                                                    # Sets the User Class to use with FOSUserBundle. This may change
                                                    # because you configure a new User class for your project.

        # These templates are used to configure emails sent using the Console Mailer. You can update the value here,
        # or use the Parent Bundle functionality to create specific email templates with the same file name.
        templates:
            setpassword_txt:  ''    # Default value: 'ManhattanConsoleBundle:Email:setpassword.txt.twig'
                                    # Sets base template for sending an email

            setpassword_html: ''    # Default value: 'ManhattanConsoleBundle:Email:setpassword.html.twig'
                                    # Sets base template for sending an email

            resetting_email:  ''    # Default value: 'ManhattanConsoleBundle:Email:reset_email.html.twig'
                                    # Sets base template for resetting FOS User password.

    email:
        registration:
            subject:  Welcome to Acme Demo Console
            template: ManhattanConsoleBundle:Registration:email.txt.twig
        resetting:
            subject:  Forgot Your Password to the Console?
            template: ManhattanConsoleBundle:Resetting:email.txt.twig

    navigation:
        title:  ''              # Default value: 'Console'
                                # Title as appears in the main navigation header

        link:   ''              # Default value: 'console_index'
                                # Link as set in the main navigation header

        link_parameters: []     # Default value: []
                                # Parameters if needed for the Console Main Link. We use "{ 'subdomain': 'console' }"
                                # as the basic setup
```
