<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use App\APIError;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function validate(
        array $data,
        array $rules,
        array $messages = [],
        array $customAttributes = []
    ) {
        $validator = $this->getValidationFactory()
            ->make(
                $data,
                $rules,
                $messages,
                $customAttributes
            );

        if ($validator->fails()) {
            $errors = (new ValidationException($validator))->errors();
            $apiError = APIError::validationError('VALIDATION_ERROR', $errors);
            throw new HttpResponseException(response()->json($apiError, 400));
        }
    }

    /**
     * Function that groups an array of associative arrays by some key.
     *
     * @param {String} $key Property to sort by.
     * @param {Array} $data Array that stores multiple associative arrays.
     */
    function group_by($array, $key)
    {
        $result = array();

        foreach ($array as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }

        return $result;
    }


    /**
     * Uploads multiple files from request into uploads/directory
     *
     * @param Illuminate\Http\Request $request
     * @param string $key_validator
     * @param string $directory
     * @return array saved files paths
     */
    public function uploadMultipleFiles(Request $request, string $key_validator, string $directory, array $rules = [])
    {
        $savedFilePaths = [];
        $fileRules = array_merge(['file'], $rules);
        $fileRules = array_unique($fileRules);

        if ($files = $request->file($key_validator)) {
            foreach ($files as $file) {
                $this->validate($request->all(), [$key_validator . '[]' => $fileRules]);
                $extension = $file->getClientOriginalExtension();
                $relativeDestinationPath = 'uploads/' . $directory;
                $destinationPath = public_path($relativeDestinationPath);
                $safeName =  uniqid(substr($directory, 0, 15) . '.', true) . '.' . $extension;
                $file->move($destinationPath, $safeName);
                $savedFilePaths[] = $relativeDestinationPath . $safeName;
            }
        }

        return $savedFilePaths;
    }


    /**
     * Uploads file from request into uploads/directory
     *
     * @param Illuminate\Http\Request $request
     * @param string $key_validator
     * @param string $directory
     * @return array saved file path
     */
    public function uploadSingleFile(Request $request, string $key_validator, string $directory, array $rules = [])
    {
        $savedFilePath = null;
        $fileRules = array_merge(['file'], $rules);
        $fileRules = array_unique($fileRules);
        if ($file = $request->file($key_validator)) {
            $this->validate($request->all(), [$key_validator => $fileRules]);
            $extension = $file->getClientOriginalExtension();
            $relativeDestinationPath = 'uploads/' . $directory;
            $destinationPath = public_path($relativeDestinationPath);
            $safeName =  uniqid(substr($directory, 0, 15) . '.', true) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $savedFilePath = $relativeDestinationPath . $safeName;
        }

        return $savedFilePath;
    }
}
