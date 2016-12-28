
var util = (function(){
    "use strict";
    
    return {
        sanitizeHTMLId: function(id) {
            // Replace anything that isn't an HTML safe id character with underscore
            // Here safe means allowable by HTML5 and also safe to use in a jQuery selector
            return id.replace(/[^a-zA-Z0-9_-]/g, "_");
        }
    };
})();