<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Job;
use App\Form\JobType;
use App\Manager\JobManager;
use App\Repository\JobRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/job", name="job_")
 */
class JobController extends AbstractController
{

    private JobRepository $jobRepository;
    private JobManager $jobManager;

    public function __construct(JobRepository $jobRepository, JobManager $jobManager)
    {
        $this->jobRepository = $jobRepository;
        $this->jobManager = $jobManager;
    }

    /**
     * @Route("/list", name="list", methods={"GET"})
     */
    public function listJob(PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $jobs = $this->jobRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('job/list.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/list/add", name="add", methods={"GET", "POST"})
     */
    public function addJob(Request $request): Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->jobManager->save($job);

            return $this->redirectToRoute('job_add');
        }

        return $this->render('job/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/list/{id}/modif", name="modif",  methods={"GET", "POST"})
     */
    public function modifJob(Request $request, int $id): Response
    {
        $job = $this->jobRepository->find($id);

        $form = $this->createForm(JobType::class, $job);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->jobManager->save($job);

            return $this->redirectToRoute('job_list');
        }

        return $this->render('job/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/list/{id}", name="delete",  methods={"GET", "POST"})
     */
    public function deleteJob(int $id, PaginatorInterface $paginator, Request $request): Response
    {


        
        $job = $this->jobRepository->findOne($id);
        if ($job === null) {
            throw new NotFoundHttpException('Le métier d\'id ' . $id . ' n\'existe pas.');
        }
  
        if($job->getEmployees()->count() > 0)
        {
            throw new NotFoundHttpException('Le métier d\'id ' . $id . ' est utilisé pour des employés.');
        }
   
        $pagination = $paginator->paginate(
            $jobs = $this->jobRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        $this->jobManager->removeJob($job);

        return $this->redirectToRoute('job_list', [
            'job' => $job,
            'pagination' => $pagination

        ]);
    }
}
