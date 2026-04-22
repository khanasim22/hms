<?php

namespace App\Repositories;

use App\Models\Complaint;

/**
 * Class BloodBankRepository
 *
 * @version February 17, 2020, 9:23 am UTC
 */
class ComplaintRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'title',
        'description',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Complaint::class;
    }
}
