<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\ContactType; 
use App\Repository\EmployeeRepository; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
 
    private EmployeeRepository $employeeRepository;
 

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;  
    }

    /**
     * @Route("/", name="main_homepage" , methods={"GET"})
     */
    public function homepage(): Response
    { 

        return $this->render('main/index.html.twig', [ 
        ]);
    }
  
}