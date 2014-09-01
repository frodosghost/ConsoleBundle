/**
 * Symfony Application is a javascript class that adds javascript functions
 * to the global namespace.
 *
 * @type    {Object}
 * @Author  James Rickard <james@antelopestudios.com.au>
 * @Version 1.0
 */

var symfony = (function() {

    var SymfonyApplication = function (options) {
        this.options     = options;
        this._parameters = {
            base_url: '' // Required: (String) Base URL used by Symfony Application
        };
    }

    /**
     * Determines the Base URL from the browser.
     * To be used with AJAX requests to ensure correct URL is used
     *
     * @return SymfonyApplication
     */
    SymfonyApplication.prototype.getBaseUrl = function () {
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
    };

    SymfonyApplication.prototype.get = function (parameter) {
        return (this._parameters[parameter] !== 'undefined') ? this._parameters[parameter] : false;
    }

    symfony = new SymfonyApplication();
    symfony.getBaseUrl();

    return symfony;

})();
