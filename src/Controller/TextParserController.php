<?php

declare(strict_types=1);

namespace App\Controller;

use App\Handler\TextHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TextParserController
 * @package App\Controller
 *
 */
class TextParserController extends AbstractController
{
    /**
     * @var TextHandler $textHandler
     */
    private $textHandler;

    public function __construct(TextHandler $textHandler)
    {
        $this->textHandler = $textHandler;
    }

    /**
     * @Route("/", methods={"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('index.html');
    }

    /**
     * @Route("/parser", methods={"POST"})
     * @param Request $request
     *
     * @return Response
     */
    public function parser(Request $request): Response
    {
        $htmlText = $this->textHandler->getHtmlText($request->getContent());

        $response = new Response(
            $htmlText,
            Response::HTTP_OK
        );

        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }
}