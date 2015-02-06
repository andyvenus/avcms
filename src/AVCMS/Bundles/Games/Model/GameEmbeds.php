<?php

namespace AVCMS\Bundles\Games\Model;

use AV\Model\Model;

class GameEmbeds extends Model
{
    public function getTable()
    {
        return 'game_embeds';
    }

    public function getSingular()
    {
        return 'game_embed';
    }

    public function getEntity()
    {
        return null;
    }

    public function getEmbedTemplate($fileExtension)
    {
        $result = $this->query()->where('extension', $fileExtension)->first(\PDO::FETCH_ASSOC);

        if (isset($result['template'])) {
            return $result['template'];
        }

        return null;
    }
}
