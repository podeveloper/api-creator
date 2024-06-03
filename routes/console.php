<?php

use App\Stubbers\ContentCreator;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

function handleResult($result, $command)
{
    if ($result['status'] == 'error') {
        $command->error($result['message']);
    } else {
        $command->info($result['message']);
    }
}

function appendApiRoutes($name)
{
    // Get the current content of api.php
    $path = base_path('routes/api.php');
    $contents = file_get_contents($path);

    // Construct the use statement
    $useStatement = "\n" . "use App\Http\Controllers\Api\\" . $name . "Controller;";

    // Find the position right after the PHP opening tag
    $phpOpeningTagPosition = strpos($contents, '<?php') + 5;
    $contentsBefore = substr($contents, 0, $phpOpeningTagPosition);
    $contentsAfter = substr($contents, $phpOpeningTagPosition);

    // Insert the new use statement after the PHP opening tag and before the rest
    $updatedContents = $contentsBefore . "\n" . $useStatement . $contentsAfter;

    // Construct the new route
    $newRoute = "Route::apiResource('" . Str::lower(Str::plural($name)) . "', {$name}Controller::class);";

    // Append the new route at the end of the file
    $updatedContents .= $newRoute;

    // Update the file
    file_put_contents($path, $updatedContents);
}

Artisan::command('make:service {name}', function ($name, ContentCreator $contentCreator) {
    $result = $contentCreator->create($name, 'service');
    handleResult($result, $this);
})->describe('Create a new service class');

Artisan::command('make:repository {name}', function ($name, ContentCreator $contentCreator) {
    $result = $contentCreator->create($name, 'repository');
    handleResult($result, $this);
})->describe('Create a new repository class');

Artisan::command('make:apicontroller {name}', function ($name, ContentCreator $contentCreator) {
    $result = $contentCreator->create($name, 'controller', 'apicontroller');
    handleResult($result, $this);
})->describe('Create a new controller class');

Artisan::command('make:api {name}', function ($name) {
    $this->call('make:model', ['name' => $name, '--migration' => true, '--factory' => true, '--seed' => true]);
    $this->call('make:request', ['name' => $name . 'Request']);
    $this->call('make:resource', ['name' => $name . 'Resource']);
    $this->call('make:apicontroller', ['name' => $name]);
    $this->call('make:service', ['name' => $name]);
    $this->call('make:repository', ['name' => $name]);

    appendApiRoutes($name);
})->describe('Create new api files');
