<?php


namespace App\Modules\Auth\Resource;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Auth
 * @package App\Modules\Auth\Resource
 *
 * @property string token_type
 * @property string access_token
 * @property Carbon expires_in
 */
class Auth extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'token_type' => $this->resource->token_type,
            'access_token' => $this->resource->access_token,
            'expires_in' => $this->resource->expires_in
        ];
    }
}
