<?php

abstract class WFError {

    public static function errorHandler($errno, $errstr, $errfile, $errline, $errcontext) {
        switch ($errno) {
            default:
                if (defined('JCE_REQUEST')) {
                    return true;
                } else {
                    return false;
                }
                break;
            case E_STRICT:
                return true;
                break;
        }
    }
    
    public static function exceptionHandler($exception) {
        echo "Uncaught exception: " , $exception->getMessage(), "\n";
    }
}

// suppress E_STRICT warnings
set_error_handler(array('WFError', 'errorHandler'), E_ALL | E_STRICT);
// suppress uncaught exceptions
set_exception_handler(array('WFError', 'exceptionHandler'));

?>