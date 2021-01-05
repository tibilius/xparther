<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View as FOSView;
use Symfony\Component\HttpFoundation\Response;

class PingPongController extends \FOS\RestBundle\Controller\AbstractFOSRestController
{
    /**
     * @Rest\Route(path="ping", methods={"GET"})
     * @Rest\View(statusCode=200)
     * @return FOSView
     */
    public function pingAction()
    {
        return ['data' => 'pong'];
    }
}