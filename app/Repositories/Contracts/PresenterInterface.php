<?php
namespace App\Repositories\Contracts;
/**
 * Interface PresenterInterface
 * @package App\Repositories\Contracts
 */
interface PresenterInterface
{
    /**
     * Prepare data to present
     *
     * @param $data
     *
     * @return mixed
     */
    public function present($data);
}
