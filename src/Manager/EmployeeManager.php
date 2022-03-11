<?php 

namespace  App\Manager;

use App\Entity\Employee;
use App\Event\EmployeeCreated;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class EmployeeManager
{

    private EntityManagerInterface $em;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function save(Employee $employes): void
    {
        $this->em->persist($employes);
        $this->em->flush();

        $this->eventDispatcher->dispatch(new EmployeeCreated($employes));
    }
}

