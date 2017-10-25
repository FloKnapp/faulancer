(function() {
    'use strict';

    // The namespace
    /** @namespace faulancer.core.debugoutput */
    var ns = faulancer.namespace('core.debugoutput');

    var _eventHandler = {

        onStackClick: function() {

            document.querySelectorAll('.stack').forEach(function(item) {
                item.style.display = 'none';
            });

            document.querySelectorAll('.previousStack').forEach(function(item) {
                item.classList.remove('selected');
            });

            this.classList.add('selected');

            document.getElementById(this.dataset.stackIdentifier).style.display = 'block';

        }

    };

    // Public scope
    ns = {

        init: function() {

            var stacks = document.querySelectorAll('.previousStack');

            stacks.forEach(function(item, i) {

                if (i !== 0) {
                    item.classList.remove('selected');
                }

                item.addEventListener('click', _eventHandler.onStackClick);
            });

        }

    };

    window.addEventListener('load', ns.init());
})();
