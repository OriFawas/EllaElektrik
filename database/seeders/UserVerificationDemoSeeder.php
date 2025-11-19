<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class UserVerificationDemoSeeder extends Seeder
{
    /**
     * This seeder is intentionally a no-op (disabled).
     */
    public function run(): void
    {
        // Disabled on request: do not seed demo verification users.
        return;
    }
}
