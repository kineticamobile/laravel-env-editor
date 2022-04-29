<?php

use Illuminate\Support\Facades\Route;
$prefix = config('env-editor.prefix','env-editor');
Route::prefix($prefix)
    ->as('env-editor.')
    ->middleware(config("env-editor.middleware", ["web"]))
    ->group(
        function () {
            Route::get(config('env-editor.show_route', 'show_env'), 'Kineticamobile\EnvEditor\Controllers\EnvEditorController@show_env')->name('show_env');

            Route::post(config('env-editor.update_route', 'update_env'), 'Kineticamobile\EnvEditor\Controllers\EnvEditorController@update_env')->name('update_env');

            Route::post(config('env-editor.restore_route', 'restore_backup'), 'Kineticamobile\EnvEditor\Controllers\EnvEditorController@restore_backup')->name('restore_backup');

        }
    );
