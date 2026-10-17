<?php

namespace App\Models;

use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Transaction extends Model
{
    /** @use HasFactory<TransactionFactory> */
    use HasFactory;

    /**
     * The list filter keys shared by the transactions page, the API, and the CSV export.
     *
     * @var list<string>
     */
    public const FILTER_KEYS = [
        'type',
        'category_id',
        'search',
        'amount_min',
        'amount_max',
        'start_date',
        'end_date',
    ];

    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'type',
        'transaction_date',
        'description',
        'receipt_path',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Reduce raw query input to the scalar, non-empty values of the shared list filters.
     *
     * @param  array<string, mixed>  $input
     * @return array<string, string>
     */
    public static function normalizeFilters(array $input): array
    {
        return array_filter(
            array_intersect_key($input, array_flip(self::FILTER_KEYS)),
            fn ($value) => is_scalar($value) && $value !== ''
        );
    }

    /**
     * Map the transactions page's ordering token to a [column, direction] pair.
     *
     * @return array{column: string, direction: string}
     */
    public static function resolveSort(?string $token): array
    {
        return match ($token) {
            'oldest' => ['column' => 'transaction_date', 'direction' => 'asc'],
            'highest' => ['column' => 'amount', 'direction' => 'desc'],
            'lowest' => ['column' => 'amount', 'direction' => 'asc'],
            default => ['column' => 'transaction_date', 'direction' => 'desc'],
        };
    }

    /**
     * Apply the shared list filters used by the page, the API, and the CSV export.
     *
     * @param  array<string, mixed>  $filters
     */
    public function scopeFiltered(Builder $query, array $filters): Builder
    {
        return $query
            ->when(filled($filters['type'] ?? null), fn (Builder $query) => $query->where('type', $filters['type']))
            ->when(filled($filters['category_id'] ?? null), fn (Builder $query) => $query->where('category_id', $filters['category_id']))
            ->when(is_string($filters['search'] ?? null) && $filters['search'] !== '', fn (Builder $query) => $query->where('description', 'like', '%'.$filters['search'].'%'))
            ->when(filled($filters['amount_min'] ?? null), fn (Builder $query) => $query->where('amount', '>=', $filters['amount_min']))
            ->when(filled($filters['amount_max'] ?? null), fn (Builder $query) => $query->where('amount', '<=', $filters['amount_max']))
            ->when(filled($filters['start_date'] ?? null), fn (Builder $query) => $query->where('transaction_date', '>=', $filters['start_date']))
            ->when(filled($filters['end_date'] ?? null), fn (Builder $query) => $query->where('transaction_date', '<=', $filters['end_date']));
    }

    /**
     * Apply the shared whitelisted ordering used by the page, the API, and the CSV export.
     */
    public function scopeSorted(Builder $query, ?string $column = null, ?string $direction = null): Builder
    {
        $column = in_array($column, ['transaction_date', 'amount', 'created_at'], true) ? $column : 'transaction_date';
        $direction = strtolower((string) $direction) === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($column, $direction);
    }

    /**
     * The years present in the user's ledger, newest first, always including the current year.
     *
     * @return Collection<int, int>
     */
    public static function ledgerYears(int $userId, int $limit = 6): Collection
    {
        $years = static::where('user_id', $userId)
            ->distinct()
            ->orderByDesc(DB::raw("strftime('%Y', transaction_date)"))
            ->limit($limit)
            ->pluck(DB::raw("strftime('%Y', transaction_date)"))
            ->map(fn ($year): int => (int) $year);

        $currentYear = (int) now()->format('Y');

        if (! $years->contains($currentYear)) {
            $years->prepend($currentYear);
        }

        return $years->sortDesc()->values();
    }

    /**
     * Get the secure URL for the transaction receipt.
     */
    public function getReceiptUrlAttribute(): ?string
    {
        if (! $this->receipt_path) {
            return null;
        }

        // Instead of a direct link to the public storage, we point to our secure controller
        $filename = basename($this->receipt_path);

        return route('receipts.show', ['filename' => $filename]);
    }

    protected function casts(): array
    {
        return [
            'transaction_date' => 'date',
        ];
    }

    protected function appends(): array
    {
        return ['receipt_url'];
    }
}
