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
    public final function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param string $prefix
     * @return array
     */
    public final function rules(string $prefix = ''): array
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
            default: return [];
        }
    }

    /**
     * @param array $array
     * @param $prefix
     * @return array
     */
    public static function appendPrefixToArrayKeys(array $array, string $prefix): array
    {
        foreach ($array as $key => $value) {
            $array[$prefix . $key] = $value;
            unset($array[$key]);
        }

        return $array;
    }

    /**
     * @param array $array
     * @param string $parent
     * @return array
     */
    public static function appendParentToArrayKeys(array $array, string $parent): array
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
}
