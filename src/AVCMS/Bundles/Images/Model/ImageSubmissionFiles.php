<?php
/**
 * User: Andy
 * Date: 11/06/15
 * Time: 10:00
 */

namespace AVCMS\Bundles\Images\Model;

class ImageSubmissionFiles extends ImageFiles
{
    public function getTable()
    {
        return 'image_submission_files';
    }

    public function getSingular()
    {
        return 'image_submission_file';
    }
}
