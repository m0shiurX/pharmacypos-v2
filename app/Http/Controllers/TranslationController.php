<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class TranslationController extends Controller
{
    public function index()
    {
        $path = base_path('lang/bn/custom.php');
        $translations = [];
        $jsonPath = base_path('lang/bn.json');
        $jsonTranslations = [];

        if (File::exists($path)) {
            try {
                $translations = include $path;
                if (! is_array($translations)) {
                    $translations = [];
                }
            } catch (\Throwable $e) {
                Log::warning('Failed to load bn custom translations: '.$e->getMessage());
                $translations = [];
            }
        }

        if (File::exists($jsonPath)) {
            try {
                $jsonTranslations = json_decode(File::get($jsonPath), true) ?: [];
            } catch (\Throwable $e) {
                $jsonTranslations = [];
            }
        }

        return view('translations.index', [
            'translations' => $translations,
            'jsonTranslations' => $jsonTranslations,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string',
            'value' => 'required|string',
            'type' => 'nullable|in:php,json',
        ]);

        $useJson = ($data['type'] ?? 'php') === 'json';
        if ($useJson) {
            $jsonPath = base_path('lang/bn.json');
            $jsonTranslations = [];
            if (File::exists($jsonPath)) {
                try {
                    $jsonTranslations = json_decode(File::get($jsonPath), true) ?: [];
                } catch (\Throwable $e) {
                    $jsonTranslations = [];
                }
            }

            $jsonTranslations[$data['key']] = $data['value'];
            $this->writeJsonFile($jsonPath, $jsonTranslations);
        } else {
            $path = base_path('lang/bn/custom.php');

            $translations = [];
            if (File::exists($path)) {
                try {
                    $translations = include $path;
                    if (! is_array($translations)) {
                        $translations = [];
                    }
                } catch (\Throwable $e) {
                    $translations = [];
                }
            }

            $translations[$data['key']] = $data['value'];

            $this->writePhpArrayFile($path, $translations);
        }

        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
        } catch (\Throwable $e) {
            // ignore cache clear errors
        }

        return redirect()->back()->with('status', ['success' => 1, 'msg' => __('messages.updated_success')]);
    }

    private function writePhpArrayFile(string $path, array $data): void
    {
        $dir = dirname($path);
        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $export = var_export($data, true);
        $content = "<?php\n\nreturn ".$export.";\n";
        File::put($path, $content);
    }

    private function writeJsonFile(string $path, array $data): void
    {
        $dir = dirname($path);
        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        $content = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        File::put($path, $content);
    }
}
