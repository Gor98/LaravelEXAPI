<?php


namespace App\Modules\Auth\Resource\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class User
 * @package App\Modules\Auth\Resource\User
 *
 * @property int id
 * @property string name
 * @property string email
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class User extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->verified_at ? true : false,
            'created_at' => format($this->created_at),
            'updated_at' => format($this->updated_at),
        ];
    }
}
