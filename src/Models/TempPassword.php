<?php
namespace codetyme\TempPassword\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TempPassword extends Model
{
    protected $fillable = ['authenticatable_id', 'authenticatable_type', 'temp_password', 'used'];

    public function authenticatable()
    {
        return $this->morphTo();
    }

    public function isValid(): bool
    {
        $expiresAt = $this->created_at->addMinutes(config('temp_pass.expiry_minutes'));
        return !$this->used && now()->lessThan($expiresAt);
    }
}
