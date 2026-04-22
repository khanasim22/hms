<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
class Complaint extends Model
{
    use HasFactory;

    public $table = 'complaints';


    protected $fillable = [
        'patient_id',
        'title',
        'description',
        'status',
        'response',
        'resolved_by',
        'resolved_at',
    ];

    public static $rules = [
        'title' => 'required|max:50',
        'description' => 'required|max:500',
    ];

    const STATUS_PENDING = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_RESOLVED = 2;
    const STATUS_REJECT = 3;
    const STATUS_HOLD = 4;

    const STATUS_ARR = [
        0 => 'Pending',
        1 => 'In Progress',
        2 => 'Resolved',
        3 => 'Reject',
        4 => 'Hold'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
