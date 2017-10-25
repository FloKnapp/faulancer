/**
 * Define namespaces in javascript
 *
 * Usage:
 * _________________________________________________________________
 *|
 *| (function(dependency1, dependency2) {
 *|
 *|   'use strict';
 *|
 *|   // The namespace
 *|   var ns = faulancer.namespace('app.myscript');
 *|
 *|   // Private scope
 *|   var _private = {
 *|
 *|     doPrivateThings: function() {
 *|       this won't get exposed in public scope...
 *|     }
 *|
 *|   };
 *|
 *|   // Helper methods
 *|   var _helper = {
 *|     define helper methods...
 *|   };
 *|
 *|   // Private fields/attributes (with example values)
 *|   var _fields = {
 *|     fromTop:    0,
 *|     fromLeft:   0
 *|   };
 *|
 *|   // Public scope
 *|   ns = {
 *|
 *|     init: function() {
 *|       do initialization...
 *|     },
 *|
 *|     doThis: function() {
 *|       do this...
 *|     },
 *|
 *|     doThat: function() {
 *|       and that...
 *|     }
 *|
 *|   };
 *|
 *|   window.addEventListener('load', faulancer.app.myscript.init);
 *|
 *| }(faulancer.app.dependency1, faulancer.app.dependency2);
 *|_________________________________________________________________
 *
 */


if (typeof faulancer === 'undefined') {
    faulancer = {};
}

faulancer.namespace = function(namespace)
{
    'use strict';

    var parts = namespace.split('.'),
        parent = faulancer,
        i;

    // strip redundant leading global
    if (parts[0] === "faulancer") {
        parts = parts.slice(1);
    }

    for (i = 0; i < parts.length; i += 1) {

        // create a property if it doesn't exist
        if (typeof parent[parts[i]] === "undefined") {
            parent[parts[i]] = {};
        }

        parent = parent[parts[i]];

    }

    return parent;
};
