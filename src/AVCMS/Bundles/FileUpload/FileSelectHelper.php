<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 11:41
 */

namespace AVCMS\Bundles\FileUpload;

use AV\FileHandler\CurlFileHandler;
use AV\FileHandler\UploadedFileHandler;
use Curl\Curl;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

class FileSelectHelper
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $fieldName;

    /**
     * @var null|array
     */
    protected $acceptedFileTypes;

    /**
     * @var null|array
     */
    protected $deniedFileTypes;

    public function __construct($path, TranslatorInterface $translator, $fieldName = 'file', array $acceptedFileTypes = null, array $deniedFileTypes = null)
    {
        $this->path = $path;
        $this->translator = $translator;
        $this->fieldName = $fieldName;
        $this->acceptedFileTypes = $acceptedFileTypes;
        $this->deniedFileTypes = $deniedFileTypes;
    }

    public function findFilesAction(Request $request)
    {
        $q = $request->get('q');

        $dir = $this->filePath();

        $itr = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));

        $data = [];

        foreach ($itr as $file) {
            if ($file->isFile() && strpos(strtolower($file->getFilename()), strtolower($q)) !== false) {
                $relativeDir = str_replace($dir, '', $file->getPath().'/'.$file->getFilename());
                $relativeDir = ($relativeDir[0] === '/' ? substr($relativeDir, 1) : $relativeDir);
                $relativePretty = str_replace('/', ' » ', $relativeDir);
                $data[] = ['id' => $relativeDir, 'text' => $relativePretty];
            }
        }

        return new JsonResponse($data);
    }

    public function uploadFilesAction(Request $request)
    {
        $httpAccept = $request->server->get('HTTP_ACCEPT', '');
        if (strpos($httpAccept, 'application/json') !== false) {
            $type = 'application/json';
        }
        else {
            $type = 'text/plain';
        }

        /**
         * @var $file UploadedFile
         */
        if ($request->files->has($this->fieldName)) {
            $file = $request->files->get($this->fieldName);
        }
        else {
            $file = $request->files->get($request->query->get('type'), null, true)['upload'];
        }

        if ($file === null) {
            return new Response(json_encode(['success' => false, 'error' => 'No file uploaded']), 200, ['Content-Type' => $type]);
        }

        if ($request->request->has('folder')) {
            $path = $this->filePath(str_replace('.', '', $request->request->get('folder')));
        }
        else {
            $path = $this->filePath($file->getClientOriginalName()[0]);
        }

        $handler = new UploadedFileHandler($this->acceptedFileTypes, $this->deniedFileTypes);

        if (($fullPath = $handler->moveFile($file, $path, null, $request->request->get('existing_files', UploadedFileHandler::EXISTS_NUMBER))) === false) {
            $fileJson = ['success' => false, 'error' => $handler->getTranslatedError($this->translator)];
        }
        else {
            $fileJson = ['success' => true, 'file' => $file->getClientOriginalName()[0].'/'.basename($fullPath)];
        }

        return new Response(json_encode($fileJson), 200, ['Content-Type' => $type]);
    }

    public function grabFileAction(Request $request)
    {
        $fileUrl = $request->get('file_url');

        if (!$fileUrl) {
            return new JsonResponse(['success' => false, 'error' => $this->translator->trans('No URL Entered')]);
        }

        $curl = new Curl();

        try {
            $file = $curl->get($fileUrl);

            $handler = new CurlFileHandler($this->acceptedFileTypes, $this->deniedFileTypes);

            $path = $this->filePath(basename($fileUrl)[0]);

            if (($fullPath = $handler->moveFile($fileUrl, $file, $path)) === false) {
                $fileJson = ['success' => false, 'error' => $handler->getTranslatedError($this->translator)];
            } else {
                $fileJson = ['success' => true, 'file' => basename($fileUrl)[0] . '/' . basename($fullPath)];
            }
        } catch (\Exception $e) {
            $fileJson = ['success' => false, 'error' => $e->getMessage()];
        }

        return new JsonResponse($fileJson);
    }

    protected function filePath($file = null)
    {
        $path = $this->path;
        if ($file !== null) {
            $path .= '/' . $file;
        }

        return $path;
    }
}
