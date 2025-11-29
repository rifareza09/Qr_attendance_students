<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'student_ip',
        'student_name',
        'username',
        'password',
        'encrypted_data',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the attendances for the student.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the QR codes for the student.
     */
    public function qrCodes(): HasMany
    {
        return $this->hasMany(QrCode::class);
    }

    /**
     * Scope a query to only include active students.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by IP address.
     */
    public function scopeByIp(Builder $query, string $ip): Builder
    {
        return $query->where('student_ip', $ip);
    }

    /**
     * Get student's total attendance count.
     */
    public function getTotalAttendanceAttribute(): int
    {
        return $this->attendances()->where('is_valid', true)->count();
    }

    /**
     * Get student's attendance this month.
     */
    public function getAttendanceThisMonthAttribute(): int
    {
        return $this->attendances()
            ->where('is_valid', true)
            ->whereYear('attendance_date', now()->year)
            ->whereMonth('attendance_date', now()->month)
            ->count();
    }

    /**
     * Get student's attendance this week.
     */
    public function getAttendanceThisWeekAttribute(): int
    {
        return $this->attendances()
            ->where('is_valid', true)
            ->whereBetween('attendance_date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->count();
    }

    /**
     * Check if student has marked attendance today.
     */
    public function hasMarkedToday(): bool
    {
        return $this->attendances()
            ->where('is_valid', true)
            ->whereDate('attendance_date', today())
            ->exists();
    }
}
