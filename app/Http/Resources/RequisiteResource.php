<?php

namespace App\Http\Resources;

use App\Enums\NalogStatusEnums;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequisiteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            "bank" => $this->bank,
            "bik" => $this->bik,
            "date_of_birth" => $this->date_of_birth,
            "fio" => $this->fio,
            "inn" => $this->inn,
            "legal_address" => $this->legal_address,
            "nalog_status" => $this->nalog_status,
            "passport" => $this->passport,
            "payment_account" => $this->payment_account,
        ];
    }
}
