<?php
  
namespace  App\Manager;

use App\Entity\ProductionTime;
use App\Event\ProductionTimeCreated;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class ProductionTimeManager
{ 
    private EntityManagerInterface $em;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function save(ProductionTime $productionTime): void
    {
        $this->em->persist($productionTime);
        $this->em->flush(); 
        
        $this->eventDispatcher->dispatch(new ProductionTimeCreated($productionTime));
    }
}

