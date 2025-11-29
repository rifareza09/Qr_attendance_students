<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class QrCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'student_id',
        'qr_code',
        'qr_file_path',
        'session_name',
        'is_used',
        'valid_until',
        'used_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_used' => 'boolean',
            'valid_until' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    /**
     * Get the student that owns the QR code.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Scope a query to only include unused QR codes.
     */
    public function scopeUnused(Builder $query): Builder
    {
        return $query->where('is_used', false);
    }

    /**
     * Scope a query to only include valid (not expired) QR codes.
     */
    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->where('valid_until', '>', now());
    }

    /**
     * Scope a query to only include expired QR codes.
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('valid_until', '<=', now());
    }

    /**
     * Check if QR code is valid (not used and not expired).
     */
    public function isValid(): bool
    {
        return !$this->is_used && $this->valid_until > now();
    }

    /**
     * Mark QR code as used.
     */
    public function markAsUsed(): bool
    {
        $this->is_used = true;
        $this->used_at = now();
        return $this->save();
    }
}
