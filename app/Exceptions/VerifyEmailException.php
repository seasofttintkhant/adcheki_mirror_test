<?php

namespace App\Exceptions;

use Exception;

class verifyEmailException extends Exception {

	/**
	 * Prettify error message output
	 * @return string
	 */
	public function errorMessage() {
		//$errorMsg = '<strong>' . $this->getMessage() . "</strong><br />\n";
		$errorMsg = $this->getMessage();
		return $errorMsg;
	}

}