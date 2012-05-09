<?php

use Behat\Mink\WebAssert;

use Behat\Mink\Exception\ExpectationException;

class CustomWebAssert extends WebAssert
{
    /**
     * Checks that current response code equals to provided one.
     *
     * @param integer $code
     *
     * @throws ExpectationException
     */
    public function statusCodeEquals($code)
    {
        // the parent class throws an ExpectationException on error
        try {
            parent::statusCodeEquals($code);
        } catch (ExpectationException $e) {
            $actual = $this->session->getStatusCode();

            // see if the first number was right, i.e. 2xx
            if (substr($code, 0, 1) == substr($actual, 0, 1)) {
                throw new ExpectationException(sprintf(
                    'Darn! Your status code was really close - it actually is %d, but %d expected.
                    Gosh, better luck on your next request!',
                    $actual,
                    $code
                ), $this->session);
            }

            // throw the error if it wasn't close
            throw $e;
        }
    }
}