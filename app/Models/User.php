<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
    ];

    /**
     * Los atributos que deben ocultarse para la serialización.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben convertirse a tipos específicos.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * RELACIÓN: Un usuario tiene muchos registros de entrenamiento (Workouts).
     */
    public function workouts(): HasMany
    {
        return $this->hasMany(Workout::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (User $user): void {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
        });
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->avatar
            ? asset('storage/'.$this->avatar)
            : null);
    }

    /**
     * Días consecutivos con al menos un entreno, contando desde el día más reciente hacia atrás.
     */
    public function workoutStreak(): int
    {
        $dates = $this->workouts()
            ->where('completed', true)
            ->orderByDesc('workout_date')
            ->get()
            ->pluck('workout_date')
            ->map(fn ($d) => Carbon::parse($d)->startOfDay()->format('Y-m-d'))
            ->unique()
            ->values();

        if ($dates->isEmpty()) {
            return 0;
        }

        $streak = 1;
        $prev = Carbon::parse($dates[0])->startOfDay();
        for ($i = 1; $i < $dates->count(); $i++) {
            $current = Carbon::parse($dates[$i])->startOfDay();
            if ($prev->diffInDays($current) === 1) {
                $streak++;
                $prev = $current;
            } else {
                break;
            }
        }

        return $streak;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}