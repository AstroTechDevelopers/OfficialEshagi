<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanMonthlySummary extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
        'title' => $this->Count . ' loans',
        'start' => $this->Date,
        'total' => 'Worth '.$this->Total,
        ];
    }
}
