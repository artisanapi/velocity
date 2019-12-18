<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    |
    */

    'path' => [
        'migration'         => database_path('migrations/'),
        'model'             => app_path('Models/'),
        'api_routes'        => base_path('routes/api.php'),
        'api_request'       => app_path('Http/Requests/API/'),
        'api_controller'    => app_path('Http/Controllers/API/'),
        'api_test'          => base_path('tests/Feature/API/'),
        'schema_files'      => resource_path('model_schemas/'),
        'templates_dir'     => resource_path('artisanapi/velocity-templates/'),
        'seeder'            => database_path('seeds/'),
        'database_seeder'   => database_path('seeds/DatabaseSeeder.php'),
        'factory'           => database_path('factories/'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Namespaces
    |--------------------------------------------------------------------------
    |
    */

    'namespace' => [
        'model'             => 'App\Models',
        'api_controller'    => 'App\Http\Controllers\API',
        'api_request'       => 'App\Http\Requests\API',
        'api_test'          => 'Tests\Feature\API',
    ],

    /*
    |--------------------------------------------------------------------------
    | Model extend class
    |--------------------------------------------------------------------------
    |
    */

    'model_extend_class' => 'Illuminate\Database\Eloquent\Model',

    /*
    |--------------------------------------------------------------------------
    | API routes prefix & version
    |--------------------------------------------------------------------------
    |
    */

    'api_prefix'  => 'api',

    'api_version' => 'v1',

    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    |
    */

    'options' => [
        'softDelete' => true,
        'save_schema_file' => true,
        'tables_searchable_default' => false,
        'excluded_fields' => ['id'], // Array of columns that doesn't required while creating module
    ],

    /*
    |--------------------------------------------------------------------------
    | Prefixes
    |--------------------------------------------------------------------------
    |
    */

    'prefixes' => [
        'route' => '',  // using admin will create route('admin.?.index') type routes
        'path' => '',
        'view' => '',  // using backend will create return view('backend.?.index') type the backend views directory
        'public' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Add-Ons
    |--------------------------------------------------------------------------
    |
    */

    'add_on' => [],

    /*
    |--------------------------------------------------------------------------
    | Timestamp Fields
    |--------------------------------------------------------------------------
    |
    */

    'timestamps' => [
        'enabled'       => true,
        'created_at'    => 'created_at',
        'updated_at'    => 'updated_at',
        'deleted_at'    => 'deleted_at',
    ],

    /*
    |--------------------------------------------------------------------------
    | Save model files to `App/Models` when use `--prefix`. see #208
    |--------------------------------------------------------------------------
    |
    */
    'ignore_model_prefix' => false,

    /*
    |--------------------------------------------------------------------------
    | Specify custom doctrine mappings as per your need
    |--------------------------------------------------------------------------
    |
    */
    'from_table' => [
        'doctrine_mappings' => [],
    ],

];
