<?php

namespace App\Controller;

use App\Entity\ProductionTime;
use App\Entity\Project;
use App\Form\ProductionTimeProjectType;
use App\Form\ProjectType;
use App\Manager\ProductionTimeManager;
use App\Manager\ProjectManager;
use App\Repository\ProjectRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project", name="project_")
 */
class ProjectController extends AbstractController
{

    private ProjectRepository $projectRepository;
    private ProjectManager $projectManager;
    private ProductionTimeManager $productionTimeManager;

    public function __construct(ProjectRepository $projectRepository, ProjectManager $projectManager, ProductionTimeManager $productionTimeManager)
    {
        $this->projectRepository = $projectRepository;
        $this->projectManager = $projectManager;
        $this->productionTimeManager = $productionTimeManager;
    }

    /**
     * @Route("/list", name="list", methods={"GET"})
     */
    public function listProject(PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $projects = $this->projectRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('project/list.html.twig', [
            'pagination' => $pagination
        ]);
    }



    /**
     * @Route("/list/{id}/detail", name="detail", methods={"GET", "POST"})
     */
    public function detailProject(int $id, Request $request): Response
    {
        $project = $this->projectRepository->findOne($id);
        if ($project === null) {
            throw new NotFoundHttpException('Le projet d\'id ' . $id . 'n\'existe pas.');
        }

        $productionTime = new ProductionTime();
        $productionTime->setProject($project);

        $form = $this->createForm(ProductionTimeProjectType::class, $productionTime);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
            $this->productionTimeManager->save($productionTime);
        }

        return $this->render('project/detail.html.twig', [
            'project' => $project,
            'productionTime' => $productionTime,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/list/add", name="add", methods={"GET", "POST"})
     */
    public function addProject(Request $request): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->projectManager->save($project);
            $this->addFlash('success', 'Le projet a été ajouté'); 
            return $this->redirectToRoute('project_add');
        }

        return $this->render('project/form.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/list/{id}/modif", name="modif", methods={"GET", "POST"})
     */
    public function modifProject(Request $request, int $id): Response
    {
        $project = $this->projectRepository->find($id);

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Le projet a été modifié'); 
            $this->projectManager->save($project);

            return $this->redirectToRoute('project_list');
        }

        return $this->render('project/form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
