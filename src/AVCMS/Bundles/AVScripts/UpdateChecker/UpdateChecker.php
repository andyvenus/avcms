<?php
/**
 * User: Andy
 * Date: 31/01/15
 * Time: 13:01
 */

namespace AVCMS\Bundles\AVScripts\UpdateChecker;

use Curl\Curl;

class UpdateChecker
{
    private $appConfig;

    private $updateInfo = null;

    private $statusMessage = null;

    private $rootDir;

    const SERVER = 'http://localhost:8888/avcms-updates/public';

    public function __construct(array $appConfig, $rootDir)
    {
        $this->appConfig = $appConfig;
        $this->rootDir = $rootDir;
    }

    public function isUpToDate()
    {
        $this->checkForUpdate();

        if (!$this->updateInfo) {
            return null;
        }

        $currentVersion = explode('.', $this->appConfig['info']['version']);

        if (!isset($currentVersion[1])) {
            $currentVersion[1] = 0;
        }

        if (!isset($currentVersion[2])) {
            $currentVersion[2] = 0;
        }

        $newVersion = explode('.', $this->updateInfo->version);

        if ($newVersion[0] > $currentVersion[0]) {
            return false;
        }
        elseif($newVersion[0] < $currentVersion[0]) {
            return true;
        }

        if (isset($newVersion[1]) && isset($currentVersion[1])) {
            if ($newVersion[1] > $currentVersion[1]) {
                return false;
            }
            elseif ($newVersion[1] < $currentVersion[1]) {
                return true;
            }
        }

        if (isset($newVersion[2]) && $newVersion[2] > $currentVersion[2]) {
            return false;
        }

        return true;
    }

    public function getUpdateInfo()
    {
        return $this->updateInfo;
    }

    private function checkForUpdate()
    {
        if ($this->updateInfo || $this->updateInfo === false) {
            return;
        }

        $curl = new Curl();

        $postData = [
            'app_id' => $this->appConfig['info']['id'],
            'app_version' => $this->appConfig['info']['version'],
            'license_key' => 'ava-26c7ad5045b51cb0bdca43f18eff38'
        ];

        if (file_exists($this->rootDir.'/webmaster/license.php')) {
            $postData['license_key'] = include $this->rootDir.'/webmaster/license.php';
        }

        $response = $curl->post(self::SERVER.'/latest-version', $postData);

        if (!isset($response->success) || $response->success === false) {
            $this->statusMessage = 'Could not get update information';

            if (isset($response->error)) {
                $this->statusMessage .= ': '.$response->error;
            }

            $this->updateInfo = false;
        }

        if (is_object($response) && isset($response->version)) {
            $this->updateInfo = $response;
        }
    }

    public function getStatusMessage()
    {
        return $this->statusMessage;
    }
}
