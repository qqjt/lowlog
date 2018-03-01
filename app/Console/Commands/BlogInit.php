<?php

namespace App\Console\Commands;

use App\Role;
use App\User;
use Illuminate\Console\Command;

class BlogInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize blog';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Get owner
        $owner = User::where('email', env('BLOGGER_EMAIL'))->first();
        if ($owner===null) {
            $this->info('Owner account not existed');
            $email = env('BLOGGER_EMAIL');
            $name = env('BLOGGER_NAME');
            $password = env('BLOGGER_PASSWORD');
            if ($email && $name && $password){
                $owner = new User();
                $owner->email = $email;
                $owner->name = $name;
                $owner->password = bcrypt($password);
                $owner->save();
            }
        }
        if (!$owner)
            return false;
        $this->info("Blog owner: $owner->name, email: $owner->email");
        // Create default roles
        $roleNames = ['author', 'subscriber'];
        foreach ($roleNames as $roleName) {
            $roleStr = $roleName.'Role';
            $$roleStr = Role::where('name', $roleName)->first();
            if ($$roleStr===null) {
                $$roleStr = new Role();
                $$roleStr->name = $roleName;
                $$roleStr->save();
                $this->info("Role $roleName created");
            }
        }
        // Ensure owner has role 'author'
        if(!$owner->roles()->where('name', 'author')->exists()) {
            $roleStr = 'authorRole';
            $owner->roles()->attach($$roleStr);
        }
        $this->info('Finished!');
        return true;
    }
}
