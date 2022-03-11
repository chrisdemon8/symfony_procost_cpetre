<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\ProductionTime;
use App\Form\EmployeeType;
use App\Form\ProductionTimeType;
use App\Manager\EmployeeManager;
use App\Manager\ProductionTimeManager;
use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/employee", name="employee_", methods={"GET", "POST"})
 */
class EmployeeController extends AbstractController
{

    private EmployeeRepository $employeeRepository;
    private ProductionTimeManager $productionTimeManager;
    private EmployeeManager $employeeManager;

    public function __construct(EmployeeRepository $employeeRepository, ProductionTimeManager $productionTimeManager,  EmployeeManager $employeeManager)
    {
        $this->employeeRepository = $employeeRepository;
        $this->productionTimeManager = $productionTimeManager;
        $this->employeeManager = $employeeManager;
    }



    /**
     * @Route("/list", name="list", methods={"GET"})
     */
    public function listEmployee(PaginatorInterface $paginator, Request $request): Response
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



    /**
     * @Route("/list/add", name="add", methods={"GET", "POST"})
     */
    public function ajoutEmploye(Request $request): Response
    {
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->employeeManager->save($employee);

            return $this->redirectToRoute('employee_add');
        }

        return $this->render('employee/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/list/{id}/modif", name="modif", methods={"GET", "POST"})
     */
    public function modifEmployee(Request $request, int $id): Response
    {
        $employee = $this->employeeRepository->find($id);

        $form = $this->createForm(EmployeeType::class, $employee);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->employeeManager->save($employee);

            return $this->redirectToRoute('employee_list');
        }

        return $this->render('employee/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/list/{id}/detail", name="detail" , methods={"GET", "POST"})
     */
    public function detailEmployee(int $id, Request $request): Response
    {
        $employee = $this->employeeRepository->findOneWithDetails($id);
        if ($employee === null) {
            throw new NotFoundHttpException('L\'employé d\'id ' . $id . 'n\'existe pas.');
        }

        $productionTime = new ProductionTime();
        $productionTime->setEmployee($employee);

        $form = $this->createForm(ProductionTimeType::class, $productionTime);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->productionTimeManager->save($productionTime);
        }

        return $this->render('employee/detail.html.twig', [
            'employee' => $employee,
            'productionTime' => $productionTime,
            'form' => $form->createView(),
        ]);
    }
}
