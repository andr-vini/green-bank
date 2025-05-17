<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class HistoricTransaction extends Model
{
    protected $fillable = [
        'type_transaction', 'status', 'balance', 'responsible_user'
    ];

    protected function typeTransaction(): Attribute
    {
        return Attribute::make(
            get: function (int $value){
                switch ($value) {
                    case 0:
                        return 'Depósito';
                        break;
                    case 1:
                        return 'Transferência';
                        break;
                    case 2:
                        return 'Saque';
                        break;
                    default:
                        return 'Tipo inválido';
                        break;
                }
            }
        );
    }

    protected function balance(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => 'R$ ' . number_format($value / 100, 2, ',', '.')
        );
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::createFromDate($value)->format('d/m/Y')
        );
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: function (int $value){
                switch ($value) {
                    case 0:
                        return 'Finalizado';
                        break;
                    case 1:
                        return 'Revertido';
                        break;
                    default:
                        return 'Tipo inválido';
                        break;
                }
            }
        );
    }

    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_user', 'id');
    }
}
