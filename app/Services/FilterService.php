<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class FilterService
{
    public function filter(
        Builder $query,
        array $filters,
        ?string $sort,
        ?string $direction,
    ): void {
        // Apply filters for each field
        foreach ($filters as $field => $value) {
            // If the field is 'id', get an exact match
            if ($field === 'id') {
                $query->where($field, $value);
            } else if($field == 'comment'){
                $commentContent = $value;
                $query->whereHas('comments', function ($q) use ($commentContent) {
                    $q->where('content', 'like', '%' . $commentContent . '%');
                });
            } else {
                // For other fields, apply partial match using 'like'
                $query->where($field, 'like', '%' . $value . '%');
            }
        }

        // Apply sorting if the sort field is valid
        if ($sort && in_array($sort, $filters)) {
            $query->orderBy($sort, $direction);
        }
    }
}