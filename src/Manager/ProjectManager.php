<?php
  
namespace  App\Manager;

use App\Entity\Project;
use App\Event\ProjectCreated;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class ProjectManager
{

    private EntityManagerInterface $em;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function save(Project $project): void
    {
        $this->em->persist($project);
        $this->em->flush();

        $this->eventDispatcher->dispatch(new ProjectCreated($project));
    }

    public function addDelivered(Project $project): void
    {
        $project->setDeliveredAt(new \DateTime());

        $this->em->persist($project);
        $this->em->flush();

        $this->eventDispatcher->dispatch(new ProjectCreated($project));
    }
}

