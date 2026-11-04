<?php

namespace App\Enums;

enum ProductType: string
{
    case Game = 'game';
    case Account = 'account';
    case GameAccount = 'game_account';
    case Joki = 'joki';
    case Preorder = 'preorder';
    case Pulsa = 'pulsa';

    public function label(): string
    {
        return match ($this) {
            self::Game => 'Top-Up Game',
            self::Account => 'Akun Premium',
            self::GameAccount => 'Jual Beli Akun Game',
            self::Joki => 'Jasa Joki Rank',
            self::Preorder => 'Preorder PDH & Uniform',
            self::Pulsa => 'Pulsa & Token Listrik',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Game => 'indigo',
            self::Account => 'cyan',
            self::GameAccount => 'amber',
            self::Joki => 'emerald',
            self::Preorder => 'fuchsia',
            self::Pulsa => 'sky',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Game => '🎮',
            self::Account => '🍿',
            self::GameAccount => '👑',
            self::Joki => '⚔️',
            self::Preorder => '👕',
            self::Pulsa => '⚡',
        };
    }

    public function requiresPlayerId(): bool
    {
        return in_array($this, [self::Game, self::Pulsa], true);
    }

    public function isAccountStock(): bool
    {
        return in_array($this, [self::Account, self::GameAccount], true);
    }

    public function isPreorder(): bool
    {
        return $this === self::Preorder;
    }

    public function isPulsa(): bool
    {
        return $this === self::Pulsa;
    }
}
