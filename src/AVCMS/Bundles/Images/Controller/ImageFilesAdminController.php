<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 11:33
 */

namespace AVCMS\Bundles\Images\Controller;

use AV\FileHandler\UploadedFileHandler;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\FileUpload\FileSelectHelper;
use Symfony\Component\HttpFoundation\Request;

class ImageFilesAdminController extends AdminBaseController
{
    /**
     * @var FileSelectHelper
     */
    private $fileSelectHelper;

    public function setUp()
    {
        $this->fileSelectHelper = new FileSelectHelper($this->getParam('web_path').'/images', $this->translator, null, null, UploadedFileHandler::getPhpFiletypes());
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
