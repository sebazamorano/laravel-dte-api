<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Repositories\Repository as BaseRepository;

/**
 * Class EmployeeRepository.
 * @version May 6, 2019, 2:37 am -04
 *
 * @method Employee findWithoutFail($id, $columns = ['*'])
 * @method Employee find($id, $columns = ['*'])
 * @method Employee first($columns = ['*'])
 */
class EmployeeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company_id',
        'user_id',
        'admin',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return Employee::class;
    }
}
