<?php

namespace App\Domain\TravelRequest\Support;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class FiltersBetween implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if (!is_array($value) || count($value) !== 2) {
            return;
        }

        [$start, $end] = $value;

        try {
            $startDate = Carbon::createFromFormat('Y-m-d', $start)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $end)->endOfDay();

            $query->whereBetween($property, [$startDate, $endDate]);
        } catch (\Exception $e) {
            return;
        }
    }
}
