<?php
/**
 * User: Andy
 * Date: 13/01/15
 * Time: 10:25
 */

namespace AVCMS\Bundles\FacebookConnect\Model;

use AV\Model\ExtensionEntity;

class FacebookConnectOverflow extends ExtensionEntity
{
    public function getId()
    {
        return $this->get('id');
    }

    public function setId($id)
    {
        $this->set('id', $id);
    }

    public function getPrefix()
    {
        return 'facebook';
    }
}
