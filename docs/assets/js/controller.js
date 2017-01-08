(function(http) {

    'use strict';

    var ns   = faulancer.namespace('docs');

    ns.controller = {

        errorAction: function() {

            document.getElementById('mainContent').innerHTML = '';

        },

        documentationAction: function() {

            var res = '/contents/landingpage.html';

            http.get(res, function(response) {

                document.getElementById('mainContent').innerHTML = response;

            }, function() {
                console.log('Error');
            });

        },

        contactAction: function() {



        }

    };

})(faulancer.namespace('docs.routing.http'));