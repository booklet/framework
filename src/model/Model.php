<?php
abstract class Model
{
    use BasicORM;
    use BasicORM2;

    use ModelAttributesTrait;
    use ModelValidationTrait;
    use ModelUntilsTrait;
    use ModelPaginateTrait;

    // Catch relations methods
    public function __call($name, $args)
    {
        // Dynamic generate Push method => $client->categoriesPush($category1);
        if (StringUntils::isInclude($name, 'Push')) {
            $relation = new Relations($this, $name, $args);

            return $relation->habtmPushObjects();

            // Dynamic generate Delete method => $client->categoriesDelete($category1);
        } elseif (StringUntils::isInclude($name, 'Delete')) {
            $relation = new Relations($this, $name, $args);

            return $relation->habtmDeleteObjects();

            // Relation with paginate
        } elseif (StringUntils::isInclude($name, 'WithPaginate')) {
            $name = str_replace('WithPaginate', '', $name);
            $relation = new Relations($this, $name, $args);
            $paginate = new BKTPaginate(get_class($this), $args[1]);
            $results = $relation->getRelationsObjects($paginate->updateParamsForResults($args[0]));
            $results_count = $relation->getRelationsObjects($paginate->updateParamsForCount($args[0]));

            return [$results, $paginate->getDataForPagination($results_count)];

            // Dynamic generate relations methods => $client->categories();
        } elseif (Relations::isRelationMethod($this, $name)) {
            $relation = new Relations($this, $name, $args);

            return $relation->getRelationsObjects();

            // If not Push, Delete or relation
        } else {
            trigger_error('Call to undefined method ' . __CLASS__ . '::' . $name . '()', E_USER_ERROR);
        }
    }
}
