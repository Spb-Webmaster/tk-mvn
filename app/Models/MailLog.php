<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    protected $fillable = [
        'form',
        'subject',
        'recipients',
        'payload',
        'status',
        'error',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'recipients' => 'array',
            'payload'    => 'array',
            'sent_at'    => 'datetime',
        ];
    }
}
