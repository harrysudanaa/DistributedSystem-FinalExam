<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'gender' => $this->gender,
            'address' => $this->address,
            'email' => $this->email,
<<<<<<< HEAD
=======
            'role' => $this->role,
            'image' => $this->image,
>>>>>>> ae74f5c5e70b6cf5bb7a5e736dfc19a27856d60e
        ];
    }
}
