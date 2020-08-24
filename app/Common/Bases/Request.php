<?php

namespace App\Common\Bases;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param string $prefix
     * @return array
     */
    public function rules($prefix = ''): array
    {
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                return self::appendParentToArrayKeys($this->getRules(), $prefix);
            case 'POST':
                return self::appendParentToArrayKeys($this->postRules(), $prefix);
            case 'PUT':
            case 'PATCH':
                return self::appendParentToArrayKeys($this->putRules(), $prefix);
        }
    }

    /**
     * @param null $keys
     * @return array
     */
    public function all($keys = null)
    {
        $data = parent::all();
        foreach ($this->route()->parameters() as $param_key => $param_value) {
            $data[$param_key] = $param_value;
        }

        $newData = [];

        foreach ($data as $key => $value) {
            if (is_int_value($value)) {
                $value = (int) $value;
            }

            $newData[$key] = $value;
        }
        return $newData;
    }

    public static function appendPrefixToArrayKeys(array $array, $prefix): array
    {
        foreach ($array as $key => $value) {
            $array[$prefix . $key] = $value;
            unset($array[$key]);
        }

        return $array;
    }

    public static function appendParentToArrayKeys(array $array, $parent): array
    {
        if ($parent === '') {
            return $array;
        }
        return self::appendPrefixToArrayKeys($array, $parent . '.');
    }

    /**
     * Get the validation rules that apply to the post request.
     *
     * @return array
     */
    abstract protected function postRules(): array;

    /**
     * Get the validation rules that apply to the put/patch request.
     *
     * @return array
     */
    protected function putRules(): array
    {
        return $this->postRules();
    }

    /**
     * Get the validation rules that apply to the get/delete request.
     *
     * @return array
     */
    protected function getRules(): array
    {
        return count(request()->all()) ? $this->postRules() : [];
    }

    /**
     * @param Request $requestModel
     * @param string $title
     * @param array $rules
     * @return array
     */
    public function attachRules(Request $requestModel, string $title, array $rules)
    {
        $requestModel->setMethod($this->method());
        return array_merge($rules, $requestModel->rules($title));
    }
}
