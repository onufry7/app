<?php

declare(strict_types=1);


namespace App\Src;


use PDO;


/**
 * Main application controller
 * 
 */
class Controller
{
    protected PDO $pdo;


	/**
	 * Initializes the controller.
	 *
	 * Sets up the PDO connection using the DB class.
	 * 
	 */
	public function __construct()
	{
		$db = new DB();
		$this->pdo = $db->pdo();
	}


	/**
	 * Prepares a standardized response array for Ajax.
	 *
	 * @param bool  $isSave Indicates whether the operation was successful.
	 * @param array $errors Optional array of error messages; defaults to an empty array.
	 *
	 * @return array Returns an array with keys:
	 * 					- 'success' => bool
	 * 					- 'errors'  => array
	 */
	protected function createAjaxResponse(bool $isSave, array $errors = [], $response = null): array
	{
		return [
			'success' => $isSave,
			'errors' => $errors,
			'response' => $response,
		];
	}
}