jQuery('document').ready(function() {
        document.formvalidator.setHandler('cargo-name',
                function (value) {
                        regex=/^[^0-9]+$/;
                        return regex.test(value);
        });
        document.formvalidator.setHandler('cargo-catid',
                function (value) {
                        if(value==0)
                        	return false;

                        return true;
        });
 })