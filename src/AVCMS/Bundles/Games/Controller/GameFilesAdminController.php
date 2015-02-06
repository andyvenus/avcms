<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 11:33
 */

namespace AVCMS\Bundles\Games\Controller;

use AV\FileHandler\UploadedFileHandler;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\FileUpload\FileSelectHelper;
use Symfony\Component\HttpFoundation\Request;

class GameFilesAdminController extends AdminBaseController
{
    /**
     * @var FileSelectHelper
     */
    private $fileSelectHelper;

    /**
     * @var FileSelectHelper
     */
    private $thumbnailSelectHelper;

    public function setUp()
    {
        $this->fileSelectHelper = new FileSelectHelper($this->getParam('web_path').'/games', $this->translator);
        $this->thumbnailSelectHelper = new FileSelectHelper($this->getParam('web_path').'/game-thumbnails', $this->translator, 'thumbnail', UploadedFileHandler::getImageFiletypes());
    }

    public function findFilesAction(Request $request)
    {
        return $this->getFileSelectHelper($request)->findFilesAction($request);
    }

    public function uploadFilesAction(Request $request)
    {
        return $this->getFileSelectHelper($request)->uploadFilesAction($request);
    }

    public function grabFileAction(Request $request)
    {
        return $this->getFileSelectHelper($request)->grabFileAction($request);
    }

    /**
     * @param Request $request
     * @return FileSelectHelper
     */
    private function getFileSelectHelper(Request $request)
    {
        $type = str_replace('game_', '', $request->get('type', 'file'));
        if ($type != 'file') {
            $type = 'thumbnail';
        }

        $handler = $type.'SelectHelper';

        return $this->$handler;
    }
}
