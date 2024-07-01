<?php

namespace Hexters\HexaLite\Commands;

use Hexters\HexaLite\Models\HexaAdmin;
use Hexters\HexaLite\Models\HexaRole;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\password;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class AdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hexa:account { --create : Create new superadmin account }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create hexa admin account';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        if ($this->option('create')) {
            $this->createAccount();
            return;
        }
        
        table(['Name', 'E-Mail Address'], HexaAdmin::where('is_superadmin', true)->get()->map(fn($row) => [$row->name, $row->email]));
    }

    protected function createAccount()
    {
        $name = text('Full name', validate: [
            'name' => ['required', 'max:50']
        ]);
        $email = text('E-Mail Address', validate: [
            'email' => ['required', 'email', 'max:100', 'unique:hexa_admins,email']
        ]);
        $password = password('Password', validate: [
            'password' => ['required', 'min:6']
        ]);

        $admin = HexaAdmin::create([
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make($password),
            'is_superadmin' => true,
            'state' => 'active',
        ]);

        $admin->roles()->attach($this->createRole());

        table([
            'Full Name', 'E-Mail Address', 'Password'
        ], [
            [$name, $email, $password]
        ]);
    }

    protected function createRole(): HexaRole
    {
        $role = HexaRole::first();
        if (!$role) {
            $role = HexaRole::create([
                'name' => 'Superadmin',
                'permissions' => config('hexa-core.permissions.default'),
                'state' => 'active',
            ]);
        }
        return $role;
    }
}
