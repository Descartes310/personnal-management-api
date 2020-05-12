<?php

namespace App;

use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @OA\Schema(title="APIError")
 */
class APIError implements \JsonSerializable
{
    /**
     * @OA\Property(example="ERR_01")
     *
     * @var string
     */
    private $code;

    /**
     * @OA\Property(example="Certains champs sont mal remplis !")
     *
     * @var string
     */
    private $message;

    /**
     * @OA\Property(example="400")
     *
     * @var string
     */
    private $status;


    /**
     * @OA\Property(example="{ email: ['Email adress is invalid']}")
     *
     * @var object
     */
    private $errors;



    /**
     * Get the value of code
     *
     * @return  string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the value of code
     *
     * @param  string  $code
     *
     * @return  self
     */
    public function setCode(string $code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get the value of message
     *
     * @return  string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @param  string  $message
     *
     * @return  self
     */
    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of status
     *
     * @return  string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param  string  $status
     *
     * @return  self
     */
    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the errors
     *
     * @return  object
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Set the errors
     *
     * @param  object  $status
     *
     * @return  self
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;

        return $this;
    }


    /**
     * Implementation of JsonSerializable interface
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }


    /**
     * Build API Error instance for VALIDATION ERROR
     * @param string $errorCode
     * @param mixed $errors
     * @return App\APIError
     */
    public static function validationError(string $errorCode, $errors) {
        $apiError = new self();
        $apiError->setCode($errorCode);
        $apiError->setStatus("400");
        $apiError->setMessage("Validation error");
        $apiError->setErrors($errors);
        return $apiError;
    }


    /**
     * Throw new 400 error (validation error)
     * @param string $errorCode
     * @param mixed $errors
     * @throws Illuminate\Http\Exceptions\HttpResponseException
     */
    public static function throwValidationError(string $errorCode, $errors) {
        $apiError = self::validationError($errorCode, $errors);
        throw new HttpResponseException(response()->json($apiError, 400));
    }
}
