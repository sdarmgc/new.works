<?php

namespace App\Models\Publications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manuscript extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'manuscripts';
    
    public $timestamps = false;
    
    
    public function files(): HasMany
    {
        return $this->hasMany(ManuscriptItem::class)->orderBy('type', 'asc')->orderBy('sort', 'desc');
    }
}
