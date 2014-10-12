jQuery('document').ready(function() {
        document.formvalidator.setHandler('dirigente-name',
                function (value) {
                        regex=/^[^0-9\<\>\=\n\t\r]+$/;
                        return regex.test(value);
        });
        document.formvalidator.setHandler('dirigente-catid',
                function (value) {
                        if(value==0)
                        	return false;

                        return true;
        });
        document.formvalidator.setHandler('dirigente-cargo',
                function (value) {
                        if(value==0)
                            return false;

                        return true;
        });
});