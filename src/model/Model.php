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
