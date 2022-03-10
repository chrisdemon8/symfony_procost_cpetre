<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/employee", name="employee_", methods={"GET", "POST"})
 */
class EmployeeController extends AbstractController
{

    private EmployeeRepository $employeeRepository;


    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }



    /**
     * @Route("/list", name="list", methods={"GET"})
     */
    public function listEmploye(PaginatorInterface $paginator, Request $request): Response
    {

        $pagination = $paginator->paginate(
            $employees = $this->employeeRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('employee/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
