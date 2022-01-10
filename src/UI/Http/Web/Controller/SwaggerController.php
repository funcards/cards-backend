<?php

declare(strict_types=1);

namespace FC\UI\Http\Web\Controller;

use OpenApi\Annotations\OpenApi;
use OpenApi\Annotations\Server;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route('/swagger')]
final class SwaggerController
{
    public function __construct(private readonly OpenApi $openApi, private readonly LoggerInterface $logger)
    {
    }

    #[Route('/ui', 'swagger-ui', methods: 'GET')]
    public function ui(Environment $twig, Request $request): Response
    {
        $this->logger->debug('ACTION -- {method}', ['method' => __METHOD__]);

        $this->openApi->servers = [$this->getServer($request)];

        $content = $twig->render('swagger/ui.html.twig', [
            'swagger_data' => ['spec' => \json_decode($this->openApi->toJson(), true, 512, JSON_THROW_ON_ERROR)],
        ]);

        return new Response($content);
    }

    #[Route('/json', 'swagger-json', methods: 'GET')]
    public function json(Request $request): Response
    {
        $this->logger->debug('ACTION -- {method}', ['method' => __METHOD__]);

        $this->openApi->servers = [$this->getServer($request)];

        return new JsonResponse($this->openApi->toJson(), json: true);
    }

    #[Route('/yaml', 'swagger-yaml', methods: 'GET')]
    public function yaml(Request $request): Response
    {
        $this->logger->debug('ACTION -- {method}', ['method' => __METHOD__]);

        $this->openApi->servers = [$this->getServer($request)];

        return new Response($this->openApi->toYaml(), headers: ['Content-Type' => 'text/x-yaml; charset=UTF-8']);
    }

    private function getServer(Request $request): Server
    {
        return new Server(['url' => $request->getSchemeAndHttpHost().$request->getBaseUrl()]);
    }
}
