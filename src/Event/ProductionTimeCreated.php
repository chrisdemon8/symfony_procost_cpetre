<?php

namespace App\Event;

use App\Entity\ProductionTime; 

final class ProductionTimeCreated
{
    private ProductionTime $productionTime;

    public function __construct(ProductionTime $productionTime)
    {
        $this->productionTime = $productionTime;
    }

    public function getTime(): ProductionTime
    {
        return $this->productionTime;
    }
}
