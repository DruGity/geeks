<?php

namespace App\Controller;

use App\Entity\Code;
use App\Repository\CodeRepository;
use App\Services\CodeGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var  CodeRepository */
    private $codeRepository;

    /** @var  CodeGenerator */
    private $generator;

    public function __construct(EntityManagerInterface $em, CodeRepository $codeRepository, CodeGenerator $generator)
    {
        $this->em = $em;
        $this->codeRepository = $codeRepository;
        $this->generator = $generator;
    }

    /**
     * @Route("/generate", name="generate", methods={"POST"})
     */
    public function generateAction(Request $request)
    {
        $data = $request->getContent();
        $nb = $request->request->get('nb');
        $export = $request->request->get('export');

        for ($i = 0; $i < $nb; $i++) {
            $codeEntity = new Code();
            $code = $this->generator->generate();
            $codeEntity->setCode($code);
            $this->em->persist($codeEntity);
            $this->em->flush();
            $codes[] = $code;
        }

        var_dump($export);
        echo '<br>';
        var_dump($nb);
        echo '<br>';
        var_dump($codes);

        return new Response('');
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

            if ($code) {

                $json = [
                    'id' => $code->getId(),
                    'code' => $code->getCode(),
                    'date' => $code->getDate()
                ];

                return new JsonResponse(json_encode($json));

            } else {
                return new Response('Code not found', 404, array('Content-Type' => 'text/html'));
            }
        } catch (Exception $exception) {
            return new Response('', 500, array('Content-Type' => 'text/html'));
        }

    }

}
