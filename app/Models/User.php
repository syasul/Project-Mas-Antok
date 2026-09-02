<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'rank_title'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isOperatorPusat(): bool
    {
        return $this->role === 'operator_pusat' || $this->isSuperAdmin();
    }

    public function isKomandanSektor(): bool
    {
        return $this->role === 'komandan_sektor' || $this->isSuperAdmin();
    }

    public function isAuditor(): bool
    {
        return $this->role === 'auditor_intelijen' || $this->isSuperAdmin();
    }

    public function canTriggerSimulator(): bool
    {
        return in_array($this->role, ['super_admin', 'operator_pusat', 'komandan_sektor']);
    }

    public function canOperateTurret(): bool
    {
        return in_array($this->role, ['super_admin', 'operator_pusat']);
    }

    public function canExportReport(): bool
    {
        return in_array($this->role, ['super_admin', 'operator_pusat', 'komandan_sektor', 'auditor_intelijen']);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'super_admin' => 'Super Administrator (Panglima Kendali)',
            'operator_pusat' => 'Operator Pusat Komando',
            'komandan_sektor' => 'Komandan Sektor Pertahanan',
            'auditor_intelijen' => 'Auditor Intelijen Keamanan',
            default => 'Operator Taktis'
        };
    }

    public function getRoleBadgeClassAttribute(): string
    {
        return match ($this->role) {
            'super_admin' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
            'operator_pusat' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
            'komandan_sektor' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30',
            'auditor_intelijen' => 'bg-purple-500/10 text-purple-400 border-purple-500/30',
            default => 'bg-slate-500/10 text-slate-400 border-slate-500/30'
        };
    }
}
