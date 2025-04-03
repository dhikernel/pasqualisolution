<?php

declare(strict_types=1);

namespace App\Domain\TravelRequest\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TravelRequest extends Model
{
    use HasFactory, SoftDeletes;

    public const TABLE_NAME = 'travel_requests';
    public const PRIMARY_KEY = 'id';
    public const FILLABLE = [
        'user_id',
        'applicant_name',
        'destination',
        'departure_date',
        'return_date',
        'status',
    ];

    protected $table = self::TABLE_NAME;
    protected $primaryKey = self::PRIMARY_KEY;
    protected $fillable = self::FILLABLE;
    protected $dates = ['deleted_at'];
}
