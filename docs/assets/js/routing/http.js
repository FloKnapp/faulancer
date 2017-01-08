(function() {

    'use strict';

    var ns = faulancer.namespace('docs.routing');

    var _fields = {
        links: []
    };

    var _private = {



    };

    ns.http = {

        get: function(resource, callbackSuccess, callbackError) {

            var xhr = new XMLHttpRequest();
            xhr.open('GET', resource);

            xhr.onreadystatechange = function() {

                if (xhr.readyState === 4 && xhr.status === 200) {
                    callbackSuccess(xhr.responseText);
                } else if (xhr.status === 400) {
                    callbackError(xhr.responseText);
                }

            };

            xhr.overrideMimeType('text/html');

            xhr.send();

        }

    };

})();
