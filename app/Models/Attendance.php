<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'student_id',
        'student_ip',
        'qr_code',
        'attendance_date',
        'attendance_time',
        'session_name',
        'wifi_ssid',
        'is_valid',
        'marked_at',
        'modified_by',
        'modified_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'attendance_date' => 'date',
            'is_valid' => 'boolean',
            'marked_at' => 'datetime',
            'modified_at' => 'datetime',
        ];
    }

    /**
     * Get the student that owns the attendance.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the admin who modified the attendance.
     */
    public function modifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'modified_by');
    }

    /**
     * Scope a query to only include today's attendances.
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('attendance_date', today());
    }

    /**
     * Scope a query to only include valid attendances.
     */
    public function scopeValid(Builder $query): Builder
    {
        return $query->where('is_valid', true);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange(Builder $query, $from, $to): Builder
    {
        return $query->whereBetween('attendance_date', [$from, $to]);
    }

    /**
     * Scope a query to filter by student.
     */
    public function scopeForStudent(Builder $query, int $studentId): Builder
    {
        return $query->where('student_id', $studentId);
    }
}
