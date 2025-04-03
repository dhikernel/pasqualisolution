<?php

declare(strict_types=1);

namespace App\Domain\TravelRequest\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="TravelRequest",
 *     required={"user_id", "applicant_name", "destination", "departure_date", "return_date", "status"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=5, description="ID do usuário solicitante"),
 *     @OA\Property(property="applicant_name", type="string", example="João Silva"),
 *     @OA\Property(property="destination", type="string", example="Paris, França"),
 *     @OA\Property(property="departure_date", type="string", format="date", example="2025-06-15"),
 *     @OA\Property(property="return_date", type="string", format="date", example="2025-06-20"),
 *     @OA\Property(property="status", type="string", example="Aprovado", description="Status da solicitação (Pendente, Aprovado, Cancelado)"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, description="Data de exclusão (soft delete)")
 * )
 */

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
