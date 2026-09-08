<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Goal extends Model
{
    protected $fillable = [
        'title',
        'description',
        'target_date',
        'status',
        'progress',
    ];

    protected $casts = [
        'target_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class);
    }

    public function updateProgress(): void
    {
        $total = $this->milestones()->count();

        if ($total === 0) {
            $this->update([
                'progress' => 0,
            ]);

            return;
        }

        $completed = $this->milestones()
            ->where('is_completed', true)
            ->count();

        $progress = (int) round(
            ($completed / $total) * 100
        );

        $data = [
            'progress' => $progress,
        ];

        // Jangan mengubah goal yang sudah di-archive
        if ($this->status !== 'archived') {

            if ($progress === 100) {
                $data['status'] = 'completed';
            } elseif ($this->status === 'completed') {
                $data['status'] = 'active';
            }
        }

        $this->update($data);
    }
}
