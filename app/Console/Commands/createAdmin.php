<?php

namespace App\Console\Commands;

use App\Models\staff;
use Illuminate\Console\Command;

class createAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $details['name'] = $this->ask('name');
        $details['email'] = $this->ask('Email');
        $details['password'] = $this->ask('Password');
        if(staff::where('email', $details['email'])->first()) {
            $this->error('Email Alreay Exists');
        } else {
            if(staff::create(['name' => $details['name'],
            'email' => $details['email'],
            'image' => "uploads/image",
            'password' => $details['password'],
            'national_id' => rand(0, 99999999),
            'role' => 'admin'])) {
                $this->info('Super admin created');
            }

        }

    }

}
