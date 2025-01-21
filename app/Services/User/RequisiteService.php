<?php

namespace App\Services\User;

use App\Enums\NalogStatusEnums;
use App\Models\Requisite;
use Illuminate\Support\Facades\Auth;

class RequisiteService
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
