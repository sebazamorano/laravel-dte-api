<?php

namespace App\Traits;

trait OnSaveEmpresa
{
    public function save(array $options = [])
    {
        if (! $this->empresa_id) {
            $this->empresa_id = session('empresa_id');
        }

        return parent::save($options);
    }
}
