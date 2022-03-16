<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use App\Repository\ProductionTimeRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{ 
    private EmployeeRepository $employeeRepository;
    private ProjectRepository $projectRepository;
    private ProductionTimeRepository $productionTimeRepository;
 
    public function __construct(EmployeeRepository $employeeRepository, ProjectRepository  $projectRepository, ProductionTimeRepository $productionTimeRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->projectRepository = $projectRepository;
        $this->productionTimeRepository = $productionTimeRepository;
    }

    /**
     * @Route("/", name="main_homepage" , methods={"GET"})
     */
    public function homepage(): Response
    {

        $employees = $this->employeeRepository->findAll();
        $projects = $this->projectRepository->findAll();
        $finishProject = $this->projectRepository->findFinishProject(); 
        $progressProject = $this->projectRepository->findProgressProject(); 
        $totalTime = $this->productionTimeRepository->totalTime();
        $lastCreatedProject = $this->projectRepository->findLastCreated(); 
        $lastCreatedProductionTime = $this->productionTimeRepository->findLastCreatedProductionTime();
        $findBestEmployee = $this->productionTimeRepository->findBestEmployee(); 
        $findRentability = $this->productionTimeRepository->findRentability(); 
        
        return $this->render('main/index.html.twig', [
            'employees' => $employees,
            'lastCreatedProject' => $lastCreatedProject,
            'findBestEmployee' => $findBestEmployee,
            'findRentability' => $findRentability,
            'lastCreatedProductionTime' => $lastCreatedProductionTime,
            'projects' => $projects,
            'totalTime' => $totalTime,
            'finishProject' => $finishProject,
            'progressProject' => $progressProject,
        ]);
    }
}
