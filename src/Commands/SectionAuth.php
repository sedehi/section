<?php

namespace Sedehi\Section\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SectionAuth extends Command
{

    use SectionsTrait, \Illuminate\Console\AppNamespaceDetectorTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create auth controllers and views';

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
        $this->info('');
        $this->info('Available Choices :');
        $this->info('');
        $this->info('1 => Email Only');
        $this->info('2 => Email And Username');
        $this->info('3 => Email And Mobile');

        $type = null;

        while (!in_array($type,[1,2,3]))
        {
            $type = $this->ask('What type of auth do you want ? ');
        }

        switch ($type) {
            case 1:

                // Create Controllers
                if (File::exists(app_path('Http/Controllers/Auth/Controllers/Site/AuthController.php'))) {
                    $this->warn('AuthController already exists.');
                } else {
                    $this->makeDirectory('Auth','Controllers/Site');
                    $data = File::get(__DIR__.'/Template/auth/email-only/controller/AuthController.stub');
                    $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
                    File::put(app_path('Http/Controllers/Auth/Controllers/Site/AuthController.php'),$data);
                    $this->info('AuthController created successfully.');
                }

                if (File::exists(app_path('Http/Controllers/Auth/Controllers/Site/ReminderController.php'))) {
                    $this->warn('ReminderController already exists.');
                } else {
                    $data = File::get(__DIR__.'/Template/auth/email-only/controller/ReminderController.stub');
                    $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
                    File::put(app_path('Http/Controllers/Auth/Controllers/Site/ReminderController.php'),$data);
                    $this->info('ReminderController created successfully.');
                }

                // Create Mailable Class
                if (File::exists(app_path('Http/Controllers/Auth/Mail/PasswordReminderMail.php'))) {
                    $this->warn('PasswordReminderMail already exists.');
                } else {
                    $this->makeDirectory('Auth','Mail');
                    $data = File::get(__DIR__.'/Template/auth/email-only/mail/PasswordReminderMail.stub');
                    $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
                    File::put(app_path('Http/Controllers/Auth/Mail/PasswordReminderMail.php'),$data);
                    $this->info('PasswordReminderMail created successfully.');
                }

                // Create Requests
                if (File::exists(app_path('Http/Controllers/Auth/Requests/Site/AuthRequest.php'))) {
                    $this->warn('AuthRequest already exists.');
                } else {
                    $this->makeDirectory('Auth','Requests/Site');
                    $data = File::get(__DIR__.'/Template/auth/email-only/request/site/AuthRequest.stub');
                    $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
                    File::put(app_path('Http/Controllers/Auth/Requests/Site/AuthRequest.php'),$data);
                    $this->info('AuthRequest created successfully.');
                }

                if (File::exists(app_path('Http/Controllers/Auth/Requests/Site/ReminderRequest.php'))) {
                    $this->warn('ReminderRequest already exists.');
                } else {
                    $data = File::get(__DIR__.'/Template/auth/email-only/request/site/ReminderRequest.stub');
                    $data = str_replace('{{{appName}}}', $this->getAppNamespace(), $data);
                    File::put(app_path('Http/Controllers/Auth/Requests/Site/ReminderRequest.php'),$data);
                    $this->info('ReminderRequest created successfully.');
                }

                // Create views
                $viewNames = ['login','reminder','reset-password','signup',];

                $this->makeDirectory('Auth','views/site');

                foreach ($viewNames as $viewName) {
                    if (File::exists(app_path('Http/Controllers/Auth/views/site/'.$viewName.'.blade.php'))) {
                        $this->warn($viewName.'.blade.php already exists.');
                    } else {
                        $data = File::get(__DIR__.'/Template/auth/email-only/view/site/'.$viewName.'.stub');
                        File::put(app_path('Http/Controllers/Auth/views/site/'.$viewName.'.blade.php'),$data);
                        $this->info($viewName.'.blade.php created successfully.');
                    }
                }

                // Create Route
                $webRoutePath = app_path('Http/Controllers/Auth/routes/web.php');

                if (File::exists($webRoutePath)) {
                    $data = File::get(__DIR__.'/Template/auth/route.stub');

                    $written = File::append($webRoutePath,"\n\n");

                    if ($written === false) {
                        $this->error('Can\'t write to web.php file');
                    }

                    File::append($webRoutePath,$data);

                    $this->info('Auth routes added to web.php successfully.');
                } else {
                    $this->makeDirectory('Auth','routes');
                    $data = File::get(__DIR__.'/Template/auth/route.stub');
                    File::put(app_path('Http/Controllers/Auth/routes/web.php'),"<?php\n\n".$data);
                    $this->info('Route file created successfully.');
                }

                break;
            case 2:
                $this->error('Not Readey Yet !');
                break;
            case 3:
                $this->error('Not Readey Yet !');
                break;
        }
    }
}
