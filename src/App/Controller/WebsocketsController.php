<?php

declare(strict_types=1);

namespace FC\App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class WebsocketsController
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(private LoggerInterface $logger)
    {
    }

    #[Route('/ws', 'ws', methods: 'GET')]
    public function ws(Request $request): Response
    {
        $this->logger->debug('ACTION -- {method}', ['method' => __METHOD__]);

        if (null !== $request->attributes->get('ws:joinServer')) {
            if (!$this->authorizeServer($request)) {
                throw new AccessDeniedException('Access Denied.');
            }

            return new Response();
        }

        if (\is_string($topics = $request->attributes->get('ws:joinTopics'))) {
            $topics = \explode(',', $topics);
            foreach ($topics as $topic) {
                if (!$this->authorizeTopic($request, $topic)) {
                    throw new AccessDeniedException('Access Denied.');
                }
            }

            return new Response();
        }

        throw new AccessDeniedException('Access Denied.');
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function authorizeServer(Request $request): bool
    {
        // TODO
        return true;
    }

    /**
     * @param Request $request
     * @param string $topic
     * @return bool
     */
    private function authorizeTopic(Request $request, string $topic): bool
    {
        // TODO
        return true;
    }
}
