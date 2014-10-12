jQuery('document').ready(function() {
        document.formvalidator.setHandler('cargo-name',
                function (value) {
                        regex=/^[^\<\>\=\n\t\r]+$/;
                        return regex.test(value);
        });
        document.formvalidator.setHandler('cargo-catid',
                function (value) {
                        if(value==0)
                        	return false;

                        return true;
        });
 })