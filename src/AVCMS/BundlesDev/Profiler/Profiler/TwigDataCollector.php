<?php
/**
 * User: Andy
 * Date: 04/08/2014
 * Time: 11:02
 */

namespace AVCMS\BundlesDev\Profiler\Profiler;

use AVCMS\Core\View\TwigLoaderFilesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

class TwigDataCollector extends DataCollector implements DataCollectorInterface
{
    protected $twigFilesystem;

    public function __construct(TwigLoaderFilesystem $twigFilesystem)
    {
        $this->twigFilesystem = $twigFilesystem;
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['templates'] = $this->twigFilesystem->getRequestedTemplates();
    }

    public function getTemplates()
    {
        return $this->data['templates'];
    }

    public function getName()
    {
        return 'templates';
    }
}
