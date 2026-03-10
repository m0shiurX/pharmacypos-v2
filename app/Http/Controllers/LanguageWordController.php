<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class LanguageWordController extends Controller
{
    /**
     * Display the language word management page
     */
    public function index()
    {
        $languages = config('constants.langs');

        // Get available modules for translation management
        $modules = $this->getAvailableModules();

        return view('language_words.index', compact('languages', 'modules'));
    }

    /**
     * Get available modules that have language files
     */
    private function getAvailableModules()
    {
        $modules = [];
        $modulesPath = base_path('Modules');

        if (File::exists($modulesPath)) {
            $moduleDirs = File::directories($modulesPath);

            foreach ($moduleDirs as $moduleDir) {
                $moduleName = basename($moduleDir);
                $langPath = $moduleDir . '/Resources/lang';

                if (File::exists($langPath)) {
                    $modules[] = [
                        'name' => $moduleName,
                        'path' => $langPath,
                        'display_name' => ucfirst(str_replace('_', ' ', $moduleName))
                    ];
                }
            }
        }

        return $modules;
    }

    /**
     * Get existing words from language files for a specific language and module
     */
    public function getWords(Request $request)
    {
        $language = $request->input('language');
        $module = $request->input('module', 'main');
        $file_type = $request->input('file_type', 'lang_v1');

        if (!$language || !array_key_exists($language, config('constants.langs'))) {
            return response()->json(['error' => 'Invalid language'], 400);
        }

        $file_path = $this->getLanguageFilePath($module, $language, $file_type);

        if (!File::exists($file_path)) {
            return response()->json(['words' => []]);
        }

        $words = include $file_path;

        if (!is_array($words)) {
            $words = [];
        }

        return response()->json([
            'words' => $words,
            'file_path' => $file_path,
            'module' => $module,
            'file_type' => $file_type,
            'debug' => [
                'language' => $language,
                'module' => $module,
                'file_type' => $file_type,
                'file_exists' => File::exists($file_path)
            ]
        ]);
    }

    /**
     * Get the correct file path based on module and file type
     */
    private function getLanguageFilePath($module, $language, $file_type)
    {
        if ($module === 'main') {
            return lang_path($language . '/' . $file_type . '.php');
        } else {
            return base_path("Modules/{$module}/Resources/lang/{$language}/{$file_type}.php");
        }
    }

    /**
     * Add a new word to language files for selected languages and modules
     */
    public function addWord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'word_key' => 'required|string|max:255',
            'word_value' => 'required|string|max:1000',
            'languages' => 'required|array|min:1',
            'languages.*' => 'string|in:' . implode(',', array_keys(config('constants.langs'))),
            'modules' => 'required|array|min:1',
            'file_type' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $word_key = $this->formatWordKey($request->input('word_key'));
        $word_value = $request->input('word_value');
        $languages = $request->input('languages');
        $modules = $request->input('modules');
        $file_type = $request->input('file_type');

        $results = [];
        $errors = [];

        foreach ($modules as $module) {
            foreach ($languages as $language) {
                try {
                    $result = $this->addWordToLanguage($module, $language, $file_type, $word_key, $word_value);
                    $results[$module][$language] = $result;
                } catch (\Exception $e) {
                    $errors[$module][$language] = $e->getMessage();
                }
            }
        }

        return response()->json([
            'success' => true,
            'results' => $results,
            'errors' => $errors,
            'message' => 'Word added successfully to selected languages and modules',
            'debug' => [
                'word_key' => $word_key,
                'word_value' => $word_value,
                'modules' => $modules,
                'languages' => $languages,
                'file_type' => $file_type
            ]
        ]);
    }

    /**
     * Update an existing word in language files
     */
    public function updateWord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'word_key' => 'required|string|max:255',
            'word_value' => 'required|string|max:1000',
            'language' => 'required|string|in:' . implode(',', array_keys(config('constants.langs'))),
            'module' => 'required|string',
            'file_type' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $word_key = $request->input('word_key');
        $word_value = $request->input('word_value');
        $language = $request->input('language');
        $module = $request->input('module');
        $file_type = $request->input('file_type');

        try {
            $result = $this->addWordToLanguage($module, $language, $file_type, $word_key, $word_value);

            return response()->json([
                'success' => true,
                'message' => 'Word updated successfully',
                'result' => $result,
                'debug' => [
                    'word_key' => $word_key,
                    'word_value' => $word_value,
                    'module' => $module,
                    'language' => $language,
                    'file_type' => $file_type
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a word from language files
     */
    public function deleteWord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'word_key' => 'required|string|max:255',
            'language' => 'required|string|in:' . implode(',', array_keys(config('constants.langs'))),
            'module' => 'required|string',
            'file_type' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $word_key = $request->input('word_key');
        $language = $request->input('language');
        $module = $request->input('module');
        $file_type = $request->input('file_type');

        try {
            $file_path = $this->getLanguageFilePath($module, $language, $file_type);

            if (!File::exists($file_path)) {
                throw new \Exception('Language file does not exist: ' . $file_path);
            }

            $words = include $file_path;

            if (!is_array($words)) {
                $words = [];
            }

            if (!array_key_exists($word_key, $words)) {
                throw new \Exception('Word key does not exist: ' . $word_key);
            }

            unset($words[$word_key]);

            $this->writeLanguageFile($file_path, $words);

            return response()->json([
                'success' => true,
                'message' => 'Word deleted successfully',
                'file_path' => $file_path,
                'word_key' => $word_key
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Format word key by converting spaces to underscores and making it lowercase
     */
    private function formatWordKey($word_key)
    {
        return strtolower(str_replace(' ', '_', trim($word_key)));
    }

    /**
     * Add word to a specific language file
     */
    private function addWordToLanguage($module, $language, $file_type, $word_key, $word_value)
    {
        $file_path = $this->getLanguageFilePath($module, $language, $file_type);

        // Create directory if it doesn't exist
        $dir = dirname($file_path);
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        // Load existing words or create empty array
        $words = [];
        if (File::exists($file_path)) {
            $words = include $file_path;
            if (!is_array($words)) {
                $words = [];
            }
        }

        // Add or update the word
        $words[$word_key] = $word_value;

        // Write back to file
        $this->writeLanguageFile($file_path, $words);

        return [
            'module' => $module,
            'language' => $language,
            'file_type' => $file_type,
            'word_key' => $word_key,
            'word_value' => $word_value,
            'file_path' => $file_path
        ];
    }

    /**
     * Write words array to language file
     */
    private function writeLanguageFile($file_path, $words)
    {
        $content = "<?php\n\nreturn [\n";

        foreach ($words as $key => $value) {
            $content .= "    '" . addslashes($key) . "' => '" . addslashes($value) . "',\n";
        }

        $content .= "];\n";

        File::put($file_path, $content);
    }

    /**
     * Bulk add words from a text input
     */
    public function bulkAddWords(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'words_text' => 'required|string',
            'languages' => 'required|array|min:1',
            'languages.*' => 'string|in:' . implode(',', array_keys(config('constants.langs'))),
            'modules' => 'required|array|min:1',
            'file_type' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $words_text = $request->input('words_text');
        $languages = $request->input('languages');
        $modules = $request->input('modules');
        $file_type = $request->input('file_type');

        // Parse words from text (one per line or comma-separated)
        $lines = preg_split('/[\r\n]+/', $words_text);
        $words = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                // If line contains "=>" assume it's key => value format
                if (strpos($line, '=>') !== false) {
                    $parts = explode('=>', $line, 2);
                    $key = trim($parts[0]);
                    $value = trim($parts[1]);
                } else {
                    // Otherwise use the line as both key and value
                    $key = $line;
                    $value = $line;
                }

                if (!empty($key)) {
                    $words[$this->formatWordKey($key)] = $value;
                }
            }
        }

        $results = [];
        $errors = [];

        foreach ($modules as $module) {
            foreach ($languages as $language) {
                foreach ($words as $word_key => $word_value) {
                    try {
                        $this->addWordToLanguage($module, $language, $file_type, $word_key, $word_value);
                        $results[$module][$language][$word_key] = $word_value;
                    } catch (\Exception $e) {
                        $errors[$module][$language][$word_key] = $e->getMessage();
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'results' => $results,
            'errors' => $errors,
            'message' => 'Words added successfully to selected languages and modules',
            'total_words' => count($words)
        ]);
    }

    /**
     * Get available file types for a module
     */
    public function getModuleFileTypes(Request $request)
    {
        $module = $request->input('module');

        \Log::info('Getting file types for module:', ['module' => $module]);

        if ($module === 'main') {
            $fileTypes = ['lang_v1'];
            \Log::info('Main module selected, returning:', ['file_types' => $fileTypes]);
        } else {
            $langPath = base_path("Modules/{$module}/Resources/lang");
            $fileTypes = [];

            \Log::info('Checking module path:', ['path' => $langPath, 'exists' => File::exists($langPath)]);

            if (File::exists($langPath)) {
                $enPath = $langPath . '/en';
                \Log::info('Checking English path:', ['path' => $enPath, 'exists' => File::exists($enPath)]);

                if (File::exists($enPath)) {
                    $files = File::files($enPath);
                    \Log::info('Found files:', ['count' => count($files)]);

                    foreach ($files as $file) {
                        $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                        $fileTypes[] = $filename;
                        \Log::info('Added file type:', ['filename' => $filename]);
                    }
                }
            }

            \Log::info('Final file types for module:', ['module' => $module, 'file_types' => $fileTypes]);
        }

        return response()->json(['file_types' => $fileTypes]);
    }

    /**
     * Determine which file type to use based on word key
     */
    private function determineFileType($word_key)
    {
        // Default file types and their common patterns
        $fileTypes = [
            'general' => ['general', 'common', 'basic', 'default'],
            'core' => ['core', 'system', 'admin', 'user', 'auth', 'permission'],
            'report' => ['report', 'export', 'print', 'download'],
            'lang' => ['lang', 'language', 'translation']
        ];

        $word_key_lower = strtolower($word_key);

        foreach ($fileTypes as $fileType => $patterns) {
            foreach ($patterns as $pattern) {
                if (strpos($word_key_lower, $pattern) !== false) {
                    return $fileType;
                }
            }
        }

        // Default to 'general' if no pattern matches
        return 'general';
    }
}
