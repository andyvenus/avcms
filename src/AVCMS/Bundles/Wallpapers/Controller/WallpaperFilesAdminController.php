<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 12:58
 */

namespace AVCMS\Bundles\Wallpapers\Controller;

use AV\FileHandler\UploadedFileHandler;
use AVCMS\Bundles\FileUpload\FileSelectHelper;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class WallpaperFilesAdminController extends Controller
{
    /**
     * @var FileSelectHelper
     */
    private $fileSelectHelper;

    public function setUp()
    {
        $this->fileSelectHelper = new FileSelectHelper(
            $this->getParam('root_dir') . '/' . $this->bundle->config->wallpapers_dir,
            $this->translator,
            'file',
            UploadedFileHandler::getImageFiletypes()
        );
    }

    public function findFilesAction(Request $request)
    {
        return $this->fileSelectHelper->findFilesAction($request);
    }

    public function uploadFilesAction(Request $request)
    {
        return $this->fileSelectHelper->uploadFilesAction($request);
    }

    public function grabFileAction(Request $request)
    {
        return $this->fileSelectHelper->grabFileAction($request);
    }
}
