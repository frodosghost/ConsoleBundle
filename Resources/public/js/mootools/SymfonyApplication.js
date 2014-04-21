/**
 * Symfony Application is a javascript class that adds javascript functions
 * to the global namespace.
 *
 * @type    {Object}
 * @Author  James Rickard <james@antelopestudios.com.au>
 * @Version 1.0
 */

var SymfonyApplication = new Class({

    _parameters: {
        base_url: null // Required: (String) Base URL used by Symfony Application
    },

    /**
     * Constructor
     *
     * @return SymfonyApplication
     */
    initialize: function() {
        this.getBaseUrl();

        return this;
    },

    /**
     * Determines the Base URL from the browser.
     * To be used with AJAX requests to ensure correct URL is used
     *
     * @return SymfonyApplication
     */
    getBaseUrl: function() {
        if (!(typeof window.location.pathname === 'undefined')) {
            if (new RegExp(/(app_dev)/g).test(window.location.pathname)) {
                this._parameters.base_url = '/app_dev.php';
            }
        } else if (!(typeof document.URL === 'undefined')) {
            if (new RegExp(/(app_dev)/g).test(document.URL)) {
                this._parameters.base_url = '/app_dev.php';
            }
        }

        return this;
    },

    get: function(parameter) {
        return (this._parameters[parameter] !== 'undefined') ? this._parameters[parameter] : false;
    },
});

var symfony = new SymfonyApplication();
