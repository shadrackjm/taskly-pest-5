<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Policies\TaskPolicy;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $description
 * @property TaskPriority $priority
 * @property Carbon|null $due_date
 * @property Carbon|null $completed_at
 * @property-read User $user
 */
#[Fillable(['title', 'description', 'priority', 'due_date'])]
#[UsePolicy(TaskPolicy::class)]
class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'priority' => TaskPriority::class,
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isComplete(): bool
    {
        return $this->completed_at !== null;
    }

    public function isOverdue(): bool
    {
        if ($this->due_date === null || $this->isComplete()) {
            return false;
        }

        return $this->due_date->isPast();
    }

    /**
     * @param  Builder<Task>  $query
     * @param  array{search?: string|null, priority?: string|null}  $filters
     */
    #[Scope]
    protected function filter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, fn (Builder $q, string $search) => $q
            ->where('title', 'like', '%'.$search.'%'));

        $query->when(
            TaskPriority::tryFrom($filters['priority'] ?? ''),
            fn (Builder $q, TaskPriority $priority) => $q->where('priority', $priority)
        );
    }

    public function complete(): void
    {
        $this->completed_at = now();
        $this->save();
    }

    /**
     * @param  Builder<Task>  $query
     */
    #[Scope]
    protected function pending(Builder $query): void
    {
        $query->whereNull('completed_at');
    }

    /**
     * @param  Builder<Task>  $query
     */
    #[Scope]
    protected function overdue(Builder $query): void
    {
        $query->whereNull('completed_at')->whereDate('due_date', '<', now());
    }
}
