<?php

namespace App\Services\User;

use App\Models\Requisite;
use App\Services\Base\Service;
use Illuminate\Support\Facades\Auth;

class RequisiteService extends Service
{
    public function createOrUpdate($request)
    {
        $user = Auth::user();

        $requisite = $user->requisite()->first() ?? new Requisite;

        $fillableFields = $requisite->getFillable();

        foreach ($fillableFields as $field) {
            if (isset($request[$field])) {
                $requisite->$field = $request[$field];
            }
        }

        $requisite->user_id = $user->id;

        $requisite->save();

        return $requisite;
    }
}
