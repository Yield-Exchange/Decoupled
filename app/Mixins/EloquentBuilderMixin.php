<?php
namespace App\Mixins;
use Illuminate\Database\Eloquent\Builder;
class EloquentBuilderMixin extends BaseBuilder {
    public function whereLike(){
        return function ($attributes, string $search_term) {

            return $this->where(function (Builder $query) use ($attributes, $search_term) {

                foreach ($attributes as $attribute) {
                    $query->when(
                        str_contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $search_term) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $search_term) {
                                $query->where($relationAttribute, 'LIKE', "%{$search_term}%");
                            });
                        },
                        function (Builder $query) use ($attribute, $search_term) {
                            $query->orWhere($attribute, 'LIKE', "%{$search_term}%");
                        }
                    );
                }

            });
        };

    }
}