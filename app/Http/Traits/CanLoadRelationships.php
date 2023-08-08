<?php

namespace App\Http\Traits;

use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CanLoadRelationships
{
    //we have an object to load in the relation, and then we have the relations
    public function loadRelationships(Model|Builder|QueryBuilder|HasMany $for, ?array $relations = null): Model|Builder|QueryBuilder|HasMany
    {
        //we can either get relations from the input array, or if this is locally used we can get it
        //directly from the local relations
        //or this will have to be an empty array
        $relations = $relations ?? $this->relations ?? [];

        foreach ($relations as $relation) {
            $for->when($this->shouldIncludeRelation($relation), fn ($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation));
            //using the query builder we can say if the relation is in the url then load or with the relation (models load their relations and queries pass through a relation)
        }
        return $for;
    }

    //this function gets a specific relation and compares against the url if its relevant
    protected function shouldIncludeRelation(string $relation): bool
    {
        $include = request()->query('include');
        if (!$include) {
            return false;
        }
        $relations = array_map('trim', explode(',', $include));
        return in_array($relation, $relations);
    }
}
