<?php

namespace App\Models\Publications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManuscriptItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'manuscript_items';
    
    public $timestamps = false;
    
    public function publication(): BelongsTo
    {
        return $this->belongsTo(Manuscript::class);
    }
}
