<?php

namespace App\Contracts\Spatie;

use App\Exceptions\TenantDoesNotExist;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Tenant
{
    /**
     * A tenant may have various user access the application.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany;

    /**
     * A tenant may be given various roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany;
}
