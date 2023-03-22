<?php

namespace App\Entity;

use JsonSerializable;

class SearchData
{
    /**
     * @var int
     */
    public $page = 1;
    /**
     * @var string
     */
    public $q = '';
    /**
     * @var Categorie[]
     */
    public $categories = [];
    /**
     * @var null|integer
     */
    public $min;
    /**
     * @var null|integer
     */
    public $max;

}
