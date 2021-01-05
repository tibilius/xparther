<?php

namespace App\Controller;

use App\Exception\ClickFailureException;
use App\Exception\FilterByXPathException;
use App\Form\Type\ParseType;
use App\Model\ParseRequest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View as FOSView;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;

class ParserController extends AbstractFOSRestController
{
    /**
     * @var string
     */
    private $screenshotDir;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(KernelInterface $kernel, RouterInterface $router)
    {
        $this->screenshotDir = $kernel->getProjectDir() . '/var/screen/';
        $this->router = $router;
    }


    /**
     * @Rest\Route(name="api_parse", path="/api/parse", methods={"POST"})
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     required=true,
     *     description="Behaviour options",
     *     @Model(type=App\Form\Type\ParseType::class)
     * )
     * @SWG\Response(response="200", description="OK")
     * @SWG\Response(response="400", description="Bad request")
     *
     * @throws ClickFailureException
     * @throws FilterByXPathException
     * @param Request $request
     * @return FOSView
     */
    public function postParseAction(Request $request)
    {
        $parseRequest = new ParseRequest();
        $form = $this->createForm(ParseType::class, $parseRequest);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return FOSView::create(['errors' => $form->getErrors(true, false)], Response::HTTP_BAD_REQUEST);
        }
        $requestUid = uniqid();
        $screenShotDir = $this->screenshotDir . $requestUid . '/';
        if ($parseRequest->isDebug()) {
            mkdir($screenShotDir, 0755, true);
        }
        $client = \Symfony\Component\Panther\Client::createChromeClient();

        $crawler = $client->request('GET', $parseRequest->getUrl());
        if ($parseRequest->getScript()) {
            $client->executeScript($parseRequest->getScript());
            $client->wait(1);
        }

        if ($parseRequest->isDebug()) {
            $client->takeScreenshot($screenShotDir . 'init.png');
        }

        foreach ($parseRequest->getClicks() as $key => $xpath) {
            $tries = 4;
            $exception = null;
            while ($tries-- > 0) {
                try {
                    $crawler->filterXPath($xpath)->click();
                    break;
                }
                catch (\Throwable $exception) {
                    $client->wait(1);
                    continue;
                }
            }
            if ($exception) {
                throw new ClickFailureException(
                    "Clicking on node with xpath `$xpath` raised exception: " . $exception->getMessage(),
                    $exception->getCode(),
                    $exception
                );
            }

            if ($parseRequest->isDebug()) {
                $client->takeScreenshot($screenShotDir . $key . '.png');
            }
        }
        $data = [];
        foreach ($parseRequest->getItems() as $item) {
            try {
                $filtered = $crawler->filterXPath($item->getXpath());
                $fExtract = $item->getAttribute()
                    ? function ($element) use ($item) { return $element->getAttribute($item->getAttribute()); }
                    : function ($element) { return $element->getText(); };
                $data[$item->getName()] = $filtered->count() > 1 ? $filtered->each($fExtract) : $fExtract($filtered);
            }
            catch (\Throwable $exception) {
                throw new FilterByXPathException(
                    "Filtering by xpath `{$item->getXpath()}` raised exception: " . $exception->getMessage(),
                    $exception->getCode(),
                    $exception
                );
            }
        }

        if ($parseRequest->isDebug()) {
            $client->takeScreenshot($screenShotDir . 'final.png');
            $debugInfo = ['debug_id' => $requestUid];
        }

        return FOSView::create(compact('data') + ($debugInfo ?? []));
    }

    /**
     * @Rest\Route(name="api_parsed_pictures_list", path="/api/parsed/{token}/", methods={"GET"})
     * @SWG\Response(response="200", description="OK")
     * @SWG\Response(response="404", description="Not found")
     * @param Request $request
     * @param string $token
     * @return FOSView
     */
    public function getParsedFilesListAction(Request $request, string $token)
    {
        $finder = new Finder();
        $finder->files()->in($this->screenshotDir . $token);
        $result = [];
        if (!$finder->count()) {
            return FOSView::create(['message' => 'Not found'], Response::HTTP_NOT_FOUND);
        }
        foreach ($finder as $file) {
            $result[] = $this->router->generate('api_parsed_picture',
                ['filepath' => $token . '/' . $file->getBasename()], RouterInterface::ABSOLUTE_PATH);

        }

        return FOSView::create(['data' => $result]);
    }

    /**
     * @Rest\Route(name="api_parsed_picture", path="/api/parsed", methods={"GET"})
     * @Rest\QueryParam(name="filepath")
     * @SWG\Response(response="200", description="OK")
     * @SWG\Response(response="404", description="Not found")
     *
     * @param Request $request
     * @return mixed
     */
    public function getParsedFileAction(Request $request)
    {
        $filepath = $this->screenshotDir . $request->query->get('filepath');
        if (!file_exists($filepath)) {
            return FOSView::create(['message' => 'NotFound'], Response::HTTP_NOT_FOUND);
        }

        return new BinaryFileResponse($filepath);
    }


}