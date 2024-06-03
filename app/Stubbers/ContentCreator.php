<?php

namespace App\Stubbers;

use Illuminate\Support\Str;

class ContentCreator
{
    public function create($modelName, $type, $stubFileName = null)
    {
        $type = ucfirst($type);
        $folderName = Str::plural($type);

        $stubFileName ??= strtolower($type);
        $className = $modelName . $type;
        $variableName = lcfirst(ucwords($modelName));
        $nameSpace = 'App\\' . $folderName;

        if ($stubFileName == 'apicontroller') {
            $nameSpace = 'App\\Http\\Controllers\\Api';
            $folderName = 'Http/Controllers/Api';
        }

        $path = app_path($folderName . '/' . $className . '.php');

        if (file_exists($path)) {
            return ['status' => 'error', 'message' => $type . ' already exists!'];
        }

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        $stub = file_get_contents("./stubs/$stubFileName.stub");
        $stub = str_replace(
            ['{{ namespace }}', '{{ class }}', '{{ ModelName }}', '{{ variableName }}'],
            [$nameSpace,  $className, $modelName, $variableName],
            $stub
        );

        file_put_contents($path, $stub);

        return ['status' => 'success', 'message' => $className . ' created successfully.'];
    }
}
