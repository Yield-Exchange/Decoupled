<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class BaseModel extends Model
{
    public function customRelation($relatedModelInstance)
    {
        return new class($relatedModelInstance) extends Relation {
            protected $relatedModelInstance;

            public function __construct($relatedModelInstance)
            {
                $this->relatedModelInstance = $relatedModelInstance;
            }

            public function getResults()
            {
                return $this->relatedModelInstance;
            }

            /**
             * @inheritDoc
             */
            public function addConstraints()
            {
                // TODO: Implement addConstraints() method.
            }

            /**
             * @inheritDoc
             */
            public function addEagerConstraints(array $models)
            {
                // TODO: Implement addEagerConstraints() method.
            }

            /**
             * @inheritDoc
             */
            public function initRelation(array $models, $relation)
            {
                // TODO: Implement initRelation() method.
            }

            /**
             * @inheritDoc
             */
            public function match(array $models, Collection $results, $relation)
            {
                // TODO: Implement match() method.
            }
        };
    }
}