<?php
/**
 * User: Andy
 * Date: 08/02/15
 * Time: 14:26
 */

namespace AVCMS\Bundles\Games\Model;

class FeedGame extends Game
{
    public function setCategory($category)
    {
        $this->set('category', $category);
    }

    public function getCategory()
    {
        return $this->get('category');
    }

    public function setProvider($provider)
    {
        $this->set('provider', $provider);
    }

    public function getProvider()
    {
        return $this->get('provider');
    }

    public function setProviderId($providerId)
    {
        $this->set('provider_id', $providerId);
    }

    public function getProviderId()
    {
        return $this->get('provider_id');
    }

    public function setTags($tags)
    {
        $this->set('tags', $tags);
    }

    public function getTags()
    {
        return $this->get('tags');
    }
}
