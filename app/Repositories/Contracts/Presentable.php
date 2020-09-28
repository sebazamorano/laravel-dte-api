<?php

namespace App\Repositories\Contracts;

/**
 * Interface Presentable
 * @package App\Repositories\Contracts
 */
interface Presentable
{
    /**
     * @param PresenterInterface $presenter
     *
     * @return mixed
     */
    public function setPresenter(PresenterInterface $presenter);
    /**
     * @return mixed
     */
    public function presenter();
}
