<?php

namespace App\Controller;

use App\Entity\Code;
use App\Repository\CodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /** @var EntityManagerInterface  */
    private $em;

    /** @var  CodeRepository */
    private $codeRepository;

    public function __construct(EntityManagerInterface $em, CodeRepository $codeRepository)
    {
        $this->em = $em;
        $this->codeRepository = $codeRepository;
    }

    /**
     * @Route("/generate", name="generate", methods={"POST"})
     */
    public function generateAction(Request $request)
    {
        $data = $request->getContent();
        $nb = $request->request->get('nb');
        $export = $request->request->get('export');
        var_dump($export);

        return new Response('okasasd');
    }

    /**
     * @return JsonResponse|Response
     * @Route("/{code}", name="get", methods={"GET"})
     */
    public function getAction($code)
    {
        try {
            /** @var Code $code */
            $code = $this->codeRepository->findOneBy(['code' => $code]);

            if($code) {
                return new JsonResponse($code);
            } else {
                return new Response('Code not found', 404, array('Content-Type' => 'text/html'));
            }
        } catch (Exception $exception) {
            return new Response('', 500, array('Content-Type' => 'text/html'));
        }

    }
}
