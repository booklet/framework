<?php
abstract class Model
{
    use BasicORM;
    use BasicORM2;

    use ModelAttributesTrait;
    use ModelValidationTrait;
    use ModelUntilsTrait;

    // Catch relations methods
    public function __call($name, $args)
    {
        // dynamic generate Push method => $client->categoriesPush($category1);
        if (StringUntils::isInclude($name, 'Push')) {
            $relation = new Relations($this, $name, $args);

            return $relation->habtmPushObjects();

            // dynamic generate Delete method => $client->categoriesDelete($category1);
        } elseif (StringUntils::isInclude($name, 'Delete')) {
            $relation = new Relations($this, $name, $args);

            return $relation->habtmDeleteObjects();

            // dynamic generate relations methods => $client->categories();
        } elseif (Relations::isRelationMethod($this, $name)) {
            $relation = new Relations($this, $name, $args);

            return $relation->getRelationsObjects();

            // if not Push, Delete or relation
        } else {
            trigger_error('Call to undefined method ' . __CLASS__ . '::' . $name . '()', E_USER_ERROR);
        }
    }
}
