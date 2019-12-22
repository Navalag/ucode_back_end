<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface HasOwner
{
    /**
     * A model has an owner.
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo;
}
