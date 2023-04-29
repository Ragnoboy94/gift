<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function generateLanguagePacks(Request $request, $sourceLanguage)
    {
        $targetLanguages = ['ar', 'zh', 'nl', 'fr', 'de', 'hi', 'id', 'it', 'ja', 'ko', 'pl', 'pt', 'ru', 'es', 'th', 'tr', 'vi'];
        $completedLanguages = [];

        foreach ($targetLanguages as $targetLanguage) {
            if ($targetLanguage !== $sourceLanguage) {
                $this->generateLanguagePack($sourceLanguage, $targetLanguage);
                $completedLanguages[] = $targetLanguage;
            }
        }

        return response("Переводы успешно созданы для следующих языков: " . implode(', ', $completedLanguages) . ".", 200);
    }

    private function generateLanguagePack($sourceLanguage, $targetLanguage)
    {
        $apiKey = config('openai.api_key');
        $client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => "Bearer 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",
                'Content-Type' => 'application/json',
            ],
        ]);

        $baseDir = resource_path('lang');
        $sourcePath = $baseDir . '/' . strtolower($sourceLanguage);
        $targetPath = $baseDir . '/' . strtolower($targetLanguage);

        if (!is_dir($sourcePath)) {
            return response("Папка с языком-источником не существует: $sourcePath", 400);
        }

        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0777, true);
        }

        $sourceFiles = scandir($sourcePath);

        foreach ($sourceFiles as $file) {
            if ($file !== '.' && $file !== '..') {
                $filePath = $sourcePath . '/' . $file;
                $translations = include $filePath;

                $translatedTexts = [];
                foreach ($translations as $key => $text) {
                    $prompt = "Translate the following text from " . strtoupper($sourceLanguage) . " to " . strtoupper($targetLanguage) . ": " . $text;
                    $response = $client->post('completions', [
                        'json' => [
                            'model' => 'text-davinci-003',
                            'prompt' => $prompt,
                            'max_tokens' => 50,
                            'temperature' => 0.5,
                        ],
                    ]);

                    $responseBody = json_decode((string) $response->getBody(), true);
                    $translatedText = $responseBody['choices'][0]['text'];
                    $translatedTexts[$key] = $translatedText;
                }

                $outputFile = $targetPath . '/' . $file;
                $content = "<?php\n\nreturn " . var_export($translatedTexts, true) . ";\n";
                file_put_contents($outputFile, $content);
            }
        }
    }


}
