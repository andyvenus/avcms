<?php
/**
 * User: Andy
 * Date: 04/08/2014
 * Time: 11:02
 */

namespace AVCMS\Core\Profiler;

use AVCMS\Core\Translation\Translator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

class TranslationsDataCollector extends DataCollector implements DataCollectorInterface
{
    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['translations'] = $this->translator->getTranslationStrings();
    }

    public function getTranslations()
    {
        return $this->data['translations'];
    }

    public function getName()
    {
        return 'translations';
    }
}