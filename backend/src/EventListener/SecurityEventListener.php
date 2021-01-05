<?php

namespace App\EventListener;


use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class SecurityEventListener
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * SecurityEventListener constructor.
     * @param Client $client
     * @param ParameterBagInterface $params
     */
    public function __construct(Client $client, ParameterBagInterface $params)
    {
        $this->client = $client;
        $this->params = $params;
    }


    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($this->params->get('kernel.environment') !== 'prod') {
            return;
        }
        if (in_array($event->getRequest()->get('_route'), $this->params->get('unauthorised_routes'))) {
            return;
        }
        if ($authHeader = $event->getRequest()->headers->get('Authorization')) {

            $response = $this->client->get('', [
                'headers' => ['Authorization' => $authHeader, 'Accept' => 'application/json'],
            ]);
            if ($response->getStatusCode() == Response::HTTP_OK) {
                return;
            }
        }
        $response = new Response(
            json_encode(['code' => Response::HTTP_UNAUTHORIZED, 'message' => 'Authorization needed']),
            Response::HTTP_UNAUTHORIZED
        );
        $event->setResponse($response);

        $event->stopPropagation();


    }


}