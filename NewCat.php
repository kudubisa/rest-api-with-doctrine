<?php

/**
 * @SWG\Definition(definition="NewPet", type="object", required={"name_cat"})
 */

Class NewsCat
{
    /**
     * @SWG\Property()
     * @var string
     */
    public $name_cat;
}


/**
 *  @SWG\Definition(
 *   definition="NewCat",
 *   type="object",
 *   allOf={
 *       @SWG\Schema(ref="#/definitions/NewCat"),
 *       @SWG\Schema(
 *           required={"name_cat"},
 *           @SWG\Property(property="name_cat", format="string", type="string")
 *       )
 *   }
 * )
 */
