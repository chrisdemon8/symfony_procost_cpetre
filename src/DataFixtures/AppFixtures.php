<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\Job;
use App\Entity\ProductionTime;
use App\Entity\Project;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /** @var ObjectManager */
    private $manager;



    public function load(ObjectManager $manager): void
    {

        $this->manager = $manager;

        $this->loadJobs();
        $this->loadEmployees();
        $this->loadProjects();
        $this->loadProductionTime();


        $this->manager->flush();
    }



    private function loadEmployees(): void
    {
 
        for ($i = 1; $i < 10; $i++) {
            $employees = (new Employee())
                ->setFirstname('Prenom ' . $i)
                ->setLastname('Nom ' . $i)
                ->setEmail('nom' . $i . '@procost.com')
                ->setDailyCost(random_int(100, 700));

            /** @var Job $jobs */
            $jobs = $this->getReference(Job::class . random_int(1, 3));
            $employees->setJob($jobs);

            $this->manager->persist($employees);
            $this->addReference(Employee::class . $i, $employees);
        }
    }

    public function getRandomDate(DateTimeImmutable $debut,DateTimeImmutable $fin):DateTimeImmutable{
        $randomTimestamp = mt_rand($debut->getTimestamp(),$fin->getTimestamp());
        $randomDate = new DateTimeImmutable();
        return  $randomDate->setTimestamp($randomTimestamp);
    }
     
    
     


    private function loadProjects(): void
    {
 
        $start = new DateTimeImmutable('2019-10-10');
        $end = new DateTimeImmutable('2023-10-10');
        

        for ($i = 1; $i < 12; $i++) { 
            $projectClass = (new Project())
                ->setName('Projet ' . $i)
                ->setDescription('Description pour le projet ' . $i)
                ->setDeliveredAt($this->getRandomDate($start,$end))
                ->setPrice(random_int(1000, 20000));
            $this->manager->persist($projectClass);
            $this->addReference(Project::class . $i, $projectClass);
        }
    }

    public function loadJobs(): void
    {
        $jobs = [
            ['Webmaster'],
            ['Web designer'],
            ['Web Developer'],
            ['SEO Manager'],
        ];

        foreach ($jobs as $key => [$label]) {
            $job = (new Job())
                ->setLabel($label);

            $this->manager->persist($job);
            $this->addReference(Job::class . $key, $job);
        }
    }


    private function loadProductionTime(): void
    {

        for ($i = 1; $i < 7; $i++) {
            /** @var Project $projects */
            $projects = $this->getReference(Project::class . $i);

            $timeCount = random_int(0, 5);
            for ($j = 0; $j < $timeCount; $j++) {
                $productionTime = (new ProductionTime())
                    ->setTime(random_int(5, 50))
                    ->setProject($projects);

                /** @var Employee $employees */
                $employees = $this->getReference(Employee::class . random_int(1, 9));
                $productionTime->setEmployee($employees);

                $this->manager->persist($productionTime);
            }
        }
    }
}
