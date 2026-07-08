<?php

namespace App\Services;

use App\Enums\UserTypes;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StaffService
{
    public function __construct(
        private readonly AccountProvisioningService $accountProvisioningService,
    ) {}

    public function paginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $search = $filters['search'] ?? null;
        $sort = $filters['sort'] ?? null;

        return User::query()
            ->role(UserTypes::Staff->value)
            ->when($search, function ($query, string $term) {
                $query->where(function ($staffQuery) use ($term) {
                    $staffQuery->where('name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            })
            ->when($sort === 'name_asc', fn ($query) => $query->orderBy('name'))
            ->when($sort === 'name_desc', fn ($query) => $query->orderByDesc('name'))
            ->when($sort === 'email_asc', fn ($query) => $query->orderBy('email'))
            ->when($sort === 'email_desc', fn ($query) => $query->orderByDesc('email'))
            ->when(
                ! in_array($sort, ['name_asc', 'name_desc', 'email_asc', 'email_desc'], true),
                fn ($query) => $query->latest()
            )
            ->paginate($perPage)
            ->withQueryString();
    }

    /** @return array{staff: User, warning: string|null} */
    public function create(array $data): array
    {
        $createdAccount = null;

        $staff = DB::transaction(function () use ($data, &$createdAccount): User {
            $createdAccount = $this->accountProvisioningService->create([
                'name' => $data['name'],
                'email' => $data['email'],
            ], UserTypes::Staff, $data['password'] ?? null);

            /** @var User $user */
            $user = $createdAccount['user'];

            return $user->fresh() ?? $user;
        });

        $warning = null;

        if (is_array($createdAccount)) {
            $warning = $this->accountProvisioningService->sendAccountCreatedMailSafely(
                $createdAccount['user'],
                $createdAccount['plain_password'],
                UserTypes::Staff,
            );
        }

        return [
            'staff' => $staff,
            'warning' => $warning,
        ];
    }

    public function update(User $staff, array $data): User
    {
        return DB::transaction(function () use ($staff, $data): User {
            $staff->fill([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            $this->accountProvisioningService->syncPassword($staff, $data['password'] ?? null);
            $staff->save();

            return $staff->fresh() ?? $staff;
        });
    }

    public function delete(User $staff): void
    {
        DB::transaction(fn () => $staff->delete());
    }

    public function resendLoginDetails(User $staff): ?string
    {
        return $this->accountProvisioningService->issueTemporaryPassword($staff, UserTypes::Staff);
    }
}
