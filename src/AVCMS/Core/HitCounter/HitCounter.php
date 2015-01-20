<?php
/**
 * User: Andy
 * Date: 19/11/14
 * Time: 20:15
 */

namespace AVCMS\Core\HitCounter;

use AV\Model\Model;
use Symfony\Component\HttpFoundation\Session\Session;

class HitCounter
{
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function registerHit(Model $model, $id, $column = 'hits', $idColumn = 'id')
    {
        $recentHits = $this->session->get('hits', []);

        if (!isset($recentHits[$model->getSingular()]) || !isset($recentHits[$model->getSingular()][$id]) || !isset($recentHits[$model->getSingular()][$id][$column])) {

            $model->query()->where($idColumn, $id)->update([$column => $model->query()->raw("$column + 1")]);

            if (!isset($recentHits[$model->getSingular()][$id])) {
                $recentHits[$model->getSingular()][$id] = [];
            }

            $recentHits[$model->getSingular()][$id][$column] = true;

            $this->session->set('hits', $recentHits);

            return true;
        }
        else {
            return false;
        }
    }
}
