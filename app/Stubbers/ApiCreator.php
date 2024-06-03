<?php

namespace App\Stubbers;

class ApiCreator
{
    public function createApi($name)
    {
        $path = app_path('Repositories/' . $name . '.php');
        $path = app_path('Services/' . $name . '.php');
        $path = app_path('Http/Controllers/Api' . $name . 'Controller.php');

        if (file_exists($path)) {
            return ['status' => 'error', 'message' => 'Repository already exists!'];
        }

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        $stub = file_get_contents('./stubs/repository.stub');

        $stub = str_replace(
            ['{{ namespace }}', '{{ class }}'],
            ['App\\Repositories', $name],
            $stub
        );

        file_put_contents($path, $stub);

        return ['status' => 'success', 'message' => 'Repository created successfully.'];
    }
}
