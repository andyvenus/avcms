<?php
/**
 * User: Andy
 * Date: 26/01/15
 * Time: 18:39
 */

namespace AVCMS\Bundles\TranslationHelper\Controller;

use AV\Form\FormBlueprint;
use AVCMS\Bundles\TranslationHelper\Form\TranslationAdminForm;
use AVCMS\Bundles\TranslationHelper\Model\Translation;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use ZipArchive;

class TranslationHelperController extends Controller
{
    /**
     * @var \AVCMS\Bundles\TranslationHelper\Model\Translations
     */
    protected $translations;

    public function setUp()
    {
        $this->translations = $this->model('Translations');
    }

    public function myTranslationsHomeAction()
    {
        if (!$this->userLoggedIn()) {
            throw new AccessDeniedException;
        }

        $userTranslations = $this->translations->query()->where('user_id', $this->activeUser()->getId())->get();

        return new Response($this->render('@TranslationHelper/my_translations_home.twig', ['translations' => $userTranslations]));
    }

    public function translationAction(Request $request, $id)
    {
        $translation = $this->translations->getOne($id);

        if (!$translation) {
            throw $this->createNotFoundException();
        }

        $allStrings = $this->getAllStrings();
        $translationInfo = [];

        $allStringsTotal = 0;
        $allTranslationsTotal = 0;

        foreach ($allStrings as $bundle => $strings) {
            $translations = $this->getBundleTranslations($bundle, $translation->getUserId(), $translation);

            $allStringsTotal = $allStringsTotal + count($strings);
            $allTranslationsTotal = $allTranslationsTotal + count($translations);

            $translationInfo[$bundle] = ['total_strings' => count($strings), 'total_translations' => count($translations), 'untranslated' => count($strings) - count($translations)];
        }

        $translation->setTotalTranslated($allTranslationsTotal);
        $this->translations->save($translation);

        return new Response($this->render('@TranslationHelper/manage_translation.twig', [
            'translations' => $translationInfo,
            'translation_config' => $translation,
            'total_strings' => $allStringsTotal,
            'total_translations' => $allTranslationsTotal
        ]));
    }

    public function bundleTranslateAction(Request $request, $id)
    {
        $translation = $this->translations->query()->where('id', $id)->where('user_id', $this->activeUser()->getId())->first();

        if (!$translation) {
            throw $this->createNotFoundException();
        }

        $bundle = $request->get('bundle', 'CmsFoundation');

        try {
            $strings = $this->getBundleStrings($bundle);
        }
        catch (\Exception $e) {
            throw $this->createNotFoundException('No strings found for this bundle');
        }

        $formBp = new FormBlueprint();

        $folder = $this->getParam('root_dir').'/webmaster/user_translations/'.$this->activeUser()->getId().'/'.$translation->getId();
        $file = $folder.'/'.$bundle.'.php';

        if (file_exists($file)) {
            $translatedStrings = include $file;
        }

        $nonTranslated = 0;
        $translated = 0;

        $showAlreadyTranslated = $request->get('already_translated', 1);

        foreach ($strings as $index => $string) {
            $default = isset($translatedStrings[$string]) ? $translatedStrings[$string] : null;

            if (!$showAlreadyTranslated && $default !== null) {
                $type = 'hidden';
            }
            else {
                $type = 'text';
            }

            $formBp->add($index, $type, [
                'label' => str_replace('js:', '', $string),
                'default' => $default
            ]);

            if ($default === null) {
                $nonTranslated++;
            }
            else {
                $translated++;
            }
        }

        $formBp->setSuccessMessage('Translations Saved');

        $form = $this->buildForm($formBp, $request);

        if ($form->isValid()) {
            $index = 0;
            $translations = [];
            $translationsJs = [];
            foreach ($form->getData() as $translationString) {
                if (isset($strings[$index]) && $translationString) {
                    $translations[$strings[$index]] = $translationString;

                    if (strpos($strings[$index], 'js:') === 0) {
                        $translationsJs[str_replace('js:', '', $strings[$index])] = $translationString;
                    }
                }
                $index++;
            }

            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            file_put_contents($file, '<?php return '.var_export($translations, true).';');

            if (!empty($translationsJs)) {
                if (!file_exists($folder.'/javascript')) {
                    mkdir($folder.'/javascript', 0777, true);
                }

                file_put_contents($folder.'/javascript/'.$bundle.'.php', '<?php return '.var_export($translationsJs, true).';');
            }
        }

        return new Response($this->render('@TranslationHelper/manage_bundle_translations.twig', [
            'bundle_name' => $bundle,
            'form' => $form->createView(),
            'translated' => $translated,
            'not_translated' => $nonTranslated,
            'translation' => $translation
        ]));
    }

    protected function getBundleTranslations($bundle, $user, Translation $translation)
    {
        $folder = $this->getParam('root_dir').'/webmaster/user_translations/'.$user.'/'.$translation->getId();
        $file = $folder.'/'.$bundle.'.php';

        if (file_exists($file)) {
            return include $file;
        }

        return [];
    }

    public function cloneAction($id)
    {
        if (!$this->userLoggedIn()) {
            throw new AccessDeniedException;
        }

        $translation = $this->translations->getOne($id);

        if (!$translation) {
            throw $this->createNotFoundException();
        }

        $translationOriginalId = $translation->getId();
        $translationOriginalUser = $translation->getUserId();

        $translation->setId(null);
        $translation->setUserId($this->activeUser()->getId());
        $translation->setPublic(0);

        $this->translations->save($translation);

        $newDir = $this->getTranslationsDir($translation->getUserId(), $translation->getId());
        mkdir($newDir, 0777, true);

        foreach (new \DirectoryIterator($this->getTranslationsDir($translationOriginalUser, $translationOriginalId)) as $translationFile) {
            if ($translationFile->isFile() && $translationFile->getExtension() === 'php') {
                 copy($translationFile->getPathname(), $newDir.'/'.$translationFile->getFilename());
            }
        }

        return $this->redirect('translate', ['id' => $translation->getId()]);
    }

    public function downloadTranslationAction($id)
    {
        $translation = $this->translations->getOne($id);

        if (!$translation) {
            throw $this->createNotFoundException();
        }

        $langName = $translation->getLangId();

        $zip = new \ZipArchive();

        $zipPath = $this->getParam('cache_dir').'/'.$langName.'-'.$translation->getId().'.zip';
        if ($zip->open($zipPath, ZipArchive::CREATE)!==TRUE) {
            exit("cannot open");
        }

        $zip->addEmptyDir($langName);

        $dir = $this->getTranslationsDir($translation->getUserId(), $translation->getId());

        if (!file_exists($dir)) {
            throw $this->createNotFoundException('This translation hasn\'t got any strings translated yet');
        }

        foreach (new \DirectoryIterator($dir) as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $zip->addFile($file->getPathname(), $langName.'/'.$file->getFilename());
            }
        }

        $zip->close();

        $headers['Content-Disposition'] = 'attachment; filename="'.$langName.'.zip"';
        $headers['Content-Type'] = 'application/zip';

        return new Response(file_get_contents($zipPath), 200, $headers);
    }

    public function addEditTranslationAction(Request $request)
    {
        $formBlueprint = new TranslationAdminForm();

        $translation = $this->translations->getOneOrNew($request->get('id', 0));

        $form = $this->buildForm($formBlueprint, $request, $translation);

        if ($form->isValid()) {
            $form->saveToEntities();

            if (!$translation->getId()) {
                $translation->setUserId($this->activeUser()->getId());
                $translation->setCreatorId($this->activeUser()->getId());
            }

            $this->translations->save($translation);

            return $this->redirect('translate', ['id' => $translation->getId()]);
        }

        return new Response($this->render('@TranslationHelper/add_edit_translation.twig', ['form' => $form->createView(), 'translation' => $translation]));
    }

    public function browseTranslationsAction()
    {
        $translations = $this->translations->query()
            ->where('public', 1)
            ->orderBy('total_translated', 'DESC')
            ->modelJoin($this->model('@users'), ['username'])
            ->get();

        $allStrings = $this->getAllStrings();
        $totalStrings = 0;
        foreach ($allStrings as $bundle => $strings) {
            $totalStrings = $totalStrings + count($strings);
        }

        return new Response($this->render('@TranslationHelper/browse_translations.twig', ['translations' => $translations, 'total_strings' => $totalStrings]));
    }

    protected function getTranslationsDir($user, $id)
    {
        return $this->getParam('root_dir').'/webmaster/user_translations/'.$user.'/'.$id;
    }

    protected function getBundleStrings($bundle)
    {
        $bundleConfig = $this->container->get('bundle_manager')->getBundleConfig($bundle);

        $bundleStrings = [];
        $stringsFile = $bundleConfig->directory.'/resources/translations/strings.txt';
        if (file_exists($stringsFile)) {
            $strings = file($stringsFile);
            foreach ($strings as $string) {
                if ($string = trim($string)) {
                    $bundleStrings[] = $string;
                }
            }
        }

        return $bundleStrings;
    }

    protected function getAllStrings()
    {
        $bundleConfigs = $this->container->get('bundle_manager')->getBundleConfigs();

        $allStrings = [];
        foreach ($bundleConfigs as $bundleConfig) {
            $stringsFile = $bundleConfig->directory.'/resources/translations/strings.txt';
            if (file_exists($stringsFile)) {
                $strings = file($stringsFile);
                foreach ($strings as $string) {
                    if ($string = trim($string)) {
                        $allStrings[$bundleConfig['name']][] = $string;
                    }
                }
            }
        }

        return $allStrings;
    }
}
