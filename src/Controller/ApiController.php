<?php

namespace App\Controller;

use App\Entity\Code;
use App\Repository\CodeRepository;
use App\Services\CodeGenerator;
use App\Services\ExcelExport;
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

    /** @var  ExcelExport */
    private $excel;

    public function __construct(
        EntityManagerInterface $em,
        CodeRepository $codeRepository,
        CodeGenerator $generator,
        ExcelExport $excel
    ){
        $this->em = $em;
        $this->codeRepository = $codeRepository;
        $this->generator = $generator;
        $this->excel = $excel;
    }

    /**
     * @return Response
     * @Route("/generate", name="generate", methods={"POST"})
     */
    public function generateAction(Request $request)
    {
        $nb = $request->request->get('nb') !== null ? $request->request->get('nb') : 1;
        $export = $request->request->get('export');

        for ($i = 0; $i < $nb; $i++) {
            $codeEntity = new Code();
            $code = $this->generator->generate();
            $codeEntity->setCode($code);
            $this->em->persist($codeEntity);

            if (($i % 50) === 0) { // на случай если кодов будет много
                $this->em->flush();
                $this->em->clear();
            }

            $codes[] = $code;
        }

        $this->em->flush();
        $this->em->clear();

        if(!empty($codes) && $export == 'xls') {
            $this->excel->export($codes);
        }
        return new Response(sprintf('%s code(s) are generated!',count($codes)));
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

                return new JsonResponse($json);

            } else {
                return new Response('Code not found', 404, array('Content-Type' => 'text/html'));
            }
        } catch (Exception $exception) {
            return new Response('', 500, array('Content-Type' => 'text/html'));
        }

    }

}
