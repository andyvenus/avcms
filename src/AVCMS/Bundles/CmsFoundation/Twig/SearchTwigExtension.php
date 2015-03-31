<?php
/**
 * User: Andy
 * Date: 15/11/14
 * Time: 12:57
 */

namespace AVCMS\Bundles\CmsFoundation\Twig;

use AV\Bundles\Form\Form\FormBuilder;
use AV\Kernel\Bundle\BundleManagerInterface;
use AVCMS\Bundles\CmsFoundation\Form\FrontendSearchForm;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

class SearchTwigExtension extends \Twig_Extension
{
    /**
     * @var \AV\Form\FormView
     */
    private $searchForm;

    protected $bundleManager;

    protected $formBuilder;

    protected $urlGenerator;

    protected $translator;

    protected $requestStack;

    public function __construct(BundleManagerInterface $bundleManager, FormBuilder $formBuilder, UrlGeneratorInterface $urlGen, TranslatorInterface $translator, RequestStack $requestStack)
    {
        $this->bundleManager = $bundleManager;
        $this->formBuilder = $formBuilder;
        $this->urlGenerator = $urlGen;
        $this->translator = $translator;
        $this->requestStack = $requestStack;
    }

    protected function getSearchForm()
    {
        if (isset($this->searchForm)) {
            return;
        }

        $currentBundle = $this->requestStack->getCurrentRequest()->attributes->get('_bundle');

        $bundleConfigs = $this->bundleManager->getBundleConfigs();
        $searchContentTypes = [];
        $selectedContent = null;

        foreach ($bundleConfigs as $bundleConfig) {
            if (isset($bundleConfig['frontend_search'])) {
                foreach ($bundleConfig['frontend_search'] as $searchConfig) {
                    try {
                        $route = $this->urlGenerator->generate($searchConfig['route'], [], UrlGeneratorInterface::ABSOLUTE_URL);
                    }
                    catch (\Exception $e) {
                        $route = '#route-'.$searchConfig['route'].'-not-found';
                    }
                    $searchContentTypes[$route] = $this->translator->trans($searchConfig['name']);

                    asort($searchContentTypes);

                    if ($currentBundle === $bundleConfig['name'] && $selectedContent === null) {
                        $selectedContent = $route;
                    }
                }
            }
        }

        $this->searchForm = $this->formBuilder->buildForm(new FrontendSearchForm($searchContentTypes, $selectedContent))->createView();
    }

    public function getGlobals()
    {
        $this->getSearchForm();

        return [
            'search_form' => $this->searchForm,
        ];
    }

    public function getName()
    {
        return 'avcms_search';
    }
}
