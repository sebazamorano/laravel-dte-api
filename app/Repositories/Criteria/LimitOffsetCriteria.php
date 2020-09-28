<?php

namespace App\Repositories\Criteria;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Http\Request;
use App\Repositories\Contracts\CriteriaInterface;

class LimitOffsetCriteria implements CriteriaInterface
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * Apply criteria in query repository.
     *
     * @param $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $limit = $this->request->get('limit', null);
        $offset = $this->request->get('offset', null);
        if ($limit) {
            $model = $model->limit($limit);
        }
        if ($offset && $limit) {
            $model = $model->skip($offset);
        }
        return $model;
    }
}
