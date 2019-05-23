window.addEvent('domready', function() {
        document.formvalidator.setHandler('categoryname',
                function (value) {
                        
                        if(value=="")
                                return false;
                        else
                                return true;
                        
        });
        
        
        function trim(str, chars) {
                return ltrim(rtrim(str, chars), chars);
        }
 
        function ltrim(str, chars) {
                chars = chars || "\\s";
                return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
        }
 
        function rtrim(str, chars) {
                chars = chars || "\\s";
               	return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
        }
});
