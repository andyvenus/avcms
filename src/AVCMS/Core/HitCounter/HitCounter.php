<?php
/**
 * User: Andy
 * Date: 19/11/14
 * Time: 20:15
 */

namespace AVCMS\Core\HitCounter;

use AV\Model\Model;
use AVCMS\Bundles\CmsFoundation\Model\Hits;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

class HitCounter
{
    /**
     * @var Hits
     */
    protected $hits;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    public function __construct(Hits $hits, RequestStack $requestStack)
    {
        $this->hits = $hits;
        $this->requestStack = $requestStack;
    }

    public function registerHit(Model $model, $id, $column = 'hits', $idColumn = 'id', $lastHitColumn = null)
    {
        $ip = $this->requestStack->getMasterRequest()->getClientIp();

        $hit = $this->hits->getHit($ip, $model->getSingular(), $id, $column);

        // If a hit was registered in the last 24hrs, don't do anything
        if ($hit && $hit->getDate() > (time() - 86400)) {
            return false;
        }
        // There was a hit but it's older than 24 hours. Lets delete it and any others still around.
        elseif ($hit) {
            $this->hits->query()->where('date', '<', time() - 86400)->delete();
            $hit = null;
        }

        if (!$hit) {
            $hit = $this->hits->newEntity();
            $hit->setColumn($column);
            $hit->setContentId($id);
            $hit->setIp($ip);
            $hit->setType($model->getSingular());
        }

        $hit->setDate(time());

        $this->hits->save($hit);

        $updateData = [$column => $model->query()->raw("$column + 1")];

        if ($lastHitColumn) {
            $updateData[$lastHitColumn] = time();
        }

        $model->query()->where($idColumn, $id)->update($updateData);

        return true;
    }
}
