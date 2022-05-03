<?php

namespace Kineticamobile\EnvEditor\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class EnvEditorController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private static function date_compare($a, $b)
    {
        $t1 = strtotime($a['created_at']);
        $t2 = strtotime($b['created_at']);
        return $t1 > $t2;
    }
    public function show_env()
    {
        $keys = DotenvEditor::getKeys();
        $backups = DotenvEditor::getBackups();
        return view('env-editor::form', compact('keys','backups'));
    }

    public function update_env(Request $request)
    {
        $vars = $request->except(['_token', '_method']);
        $editor = DotenvEditor::load();
        foreach ($vars as $key => $value) {
            if (!in_array($key, config('env-editor.exclude')) && $editor->getValue($key) != $value) {
                $editor->setKey($key, $value);
            }
        }
        if ($editor->hasChanged()) {
            if(!file_exists(storage_path('dotenv-editor/.gitignore'))) {
                file_put_contents(storage_path('dotenv-editor/.gitignore'), "*\n!.gitignore");
            }
            $editor->backup()->save();
            $this->delete_old_backups();
            
        }
        return redirect()->route('env-editor.show_env');
    }
    public function restore_backup(Request $request)
    {   
        $editor = DotenvEditor::restore($request->backup);

        return redirect()->route('env-editor.show_env');
    }
    private function delete_old_backups()
    {
        $backups = DotenvEditor::getBackups();
        usort($backups, 'self::date_compare');
        for ($i = 0; $i < count($backups) - config('env-editor.max_backups'); $i++) {
            DotenvEditor::deleteBackup($backups[$i]['filepath']);
        }
    }
}
