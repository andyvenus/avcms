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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

class SearchTwigExtension extends \Twig_Extension
{
    /**
     * @var \AV\Form\FormHandler
     */
    private $searchForm;

    public function __construct(BundleManagerInterface $bundleManager, FormBuilder $formBuilder, UrlGeneratorInterface $urlGen, TranslatorInterface $translator)
    {
        $bundleConfigs = $bundleManager->getBundleConfigs();
        $searchContentTypes = [];
        foreach ($bundleConfigs as $bundleConfig) {
            if (isset($bundleConfig['frontend_search'])) {
                foreach ($bundleConfig['frontend_search'] as $searchConfig) {
                    $searchContentTypes[$urlGen->generate($searchConfig['route'], [], UrlGeneratorInterface::ABSOLUTE_URL)] = $translator->trans($searchConfig['name']);
                }
            }
        }

        $this->searchForm = $formBuilder->buildForm(new FrontendSearchForm($searchContentTypes))->createView();
    }

    public function getGlobals()
    {
        return [
            'search_form' => $this->searchForm,
        ];
    }

    public function getName()
    {
        return 'avcms_search';
    }
}