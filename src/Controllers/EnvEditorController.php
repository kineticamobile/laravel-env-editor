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

        return view('env-editor::form', compact('keys'));
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

            $editor->backup()->save();
            $this->delete_old_backups();
        }
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
