(function(controller) {

    'use strict';

    /** @namespace faulancer.routing */
    var ns = faulancer.namespace('docs.routing');

    var _fields = {
        paths: [],
        basePath: 'faulancer',
        currentPath: '/'
    };

    var _private = {

        /**
         * Get the action
         *
         * @param {String} path
         * @returns {Function}
         */
        getAction: function(path) {

            if (controller.hasOwnProperty(path + 'Action')) {

                var ctrl   = 'controller';
                var method = path + 'Action';
                var target = faulancer['docs'][ctrl][method];

                if (typeof target === 'function') {
                    return target();
                }

            } else {

                return controller.errorAction();

            }

        },

        /**
         * Run dispatcher
         *
         * @param {String} path
         * @returns {Function}
         */
        run: function(path) {

            if (path.substr(0,1) === '/') {
                path = path.substr(1, path.length);
            }

            if (document.querySelector('a.selected')) {
                document.querySelector('a.selected').classList.remove('selected');
            }

            document.querySelector('[href="/' + path + '"]').classList.add('selected');


            return this.getAction(path);



        }

    };

    var _events = {

        onDocumentLoad: function() {
            _private.run('/documentation');
        },

        onLinkClick: function(e) {
            e.preventDefault();
            _helpers.addPath(e.target);
            _private.run(_helpers.getPath());
            _fields.currentPath = _helpers.getPath();
            return false;
        }

    };

    var _helpers = {

        addPath: function(item) {

            var path = item.getAttribute('href');
            window.history.pushState(null, 'Site', _fields.basePath + path);

        },

        getPath: function() {
            return window.location.pathname.replace(_fields.basePath, '');
        },

        detectPathChanges: function() {

            if (_fields.currentPath !== _helpers.getPath()) {

                _fields.currentPath = _helpers.getPath();
                _private.run(_helpers.getPath());

            }

        }

    };

    ns.dispatcher = {

        init: function() {

            _fields.basePath = document.querySelector('base').getAttribute('href');

            var links = document.querySelectorAll('a');

            for (var link in links) {

                if (!links.hasOwnProperty(link)) {
                    continue;
                }

                links[link].addEventListener('click', _events.onLinkClick);

            }

            _events.onDocumentLoad();

            //setInterval(_helpers.detectPathChanges, 500);

        }

    };

    window.addEventListener('load', ns.dispatcher.init);



})(faulancer.namespace('docs.controller'));