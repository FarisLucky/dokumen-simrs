<?php

namespace App\Models\Traits;

use Illuminate\Support\Arr;

trait Searchable
{

    function scopeJoinTable($query, $joins)
    {
        foreach (Arr::wrap($joins) as $item) {

            $join = $item[0];
            $joinRelation = $item[1];
            $operator = $item[2];
            $tableRelation = $item[3];

            $query->join($join, $joinRelation, $operator, $tableRelation);
        }
    }

    function scopeLeftJoinTable($query, $joins)
    {
        foreach (Arr::wrap($joins) as $item) {

            $join = $item[0];
            $joinRelation = $item[1];
            $operator = $item[2];
            $tableRelation = $item[3];

            $query->leftJoin($join, $joinRelation, $operator, $tableRelation);
        }
    }

    function scopeFilter($query, $columnKeyFilter, $columnValFilter)
    {
        for ($i = 0; $i < count($columnKeyFilter); $i++) {

            $column = $columnKeyFilter[$i];

            $explode = explode('_', $column);
            if (count($explode) <= 2) { // daftar_TANGGAL

                $table = $explode[0];

                $column = $explode[1];
            } else {

                $table = $explode[0];

                $column = $explode[1] . '_' . $explode[2];

                Arr::forget($explode, [0, 1, 2]);

                if (count($explode) > 0) {
                    foreach ($explode as $cName) {
                        $column .= '_' . $cName;
                    }
                }
            }

            $queryColumn = $table . '.' . $column;

            $val = $columnValFilter[$i];

            $query->where($queryColumn, 'LIKE', "%{$val}%");
        }
    }
}
