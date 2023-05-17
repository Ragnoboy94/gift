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
        $targetLanguages = ['en','ar', 'zh', 'nl', 'fr', 'de', 'hi', 'id', 'it', 'ja', 'ko', 'pl', 'pt', 'es', 'th', 'tr', 'vi', 'uk', 'be', 'kk', 'uz', 'az', 'tg', 'ky', 'hy', 'ka', 'lt', 'lv', 'et', 'ro'];

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
        $createdFilesInfo = [];

        foreach ($sourceFiles as $file) {
            if ($file !== '.' && $file !== '..') {
                $outputFile = $targetPath . '/' . $file;

                if (!file_exists($outputFile)) {
                    $filePath = $sourcePath . '/' . $file;
                    $translations = include $filePath;

                    if ($file === 'celebrations.php') {
                        $translatedTexts = [];
                        /*foreach ($translations as $index => $celebration) {
                            $translatedTexts[$index] = $celebration;
                            foreach ($celebration as $key => $text) {
                                if (is_string($text)) {
                                    $prompt = "Translate the following text from " . strtoupper($sourceLanguage) . " to " . strtoupper($targetLanguage) . ": " . $text;
                                    $response = $client->post('completions', [
                                        'json' => [
                                            'model' => 'text-davinci-003',
                                            'prompt' => $prompt,
                                            'max_tokens' => 1024,
                                            'temperature' => 0.5,
                                        ],
                                    ]);

                                    $responseBody = json_decode((string)$response->getBody(), true);
                                    $translatedText = $responseBody['choices'][0]['text'];
                                    $translatedTexts[$index][$key] = $translatedText;
                                }
                            }
                        }*/
                    } else {
                        $translatedTexts = [];
                        foreach ($translations as $key => $text) {
                            $prompt = "Translate the following text from " . strtoupper($sourceLanguage) . " to " . strtoupper($targetLanguage) . ": " . $text;
                            $response = $client->post('completions', [
                                'json' => [
                                    'model' => 'text-davinci-003',
                                    'prompt' => $prompt,
                                    'max_tokens' => 1024,
                                    'temperature' => 0.5,
                                ],
                            ]);

                            $responseBody = json_decode((string) $response->getBody(), true);
                            $translatedText = $responseBody['choices'][0]['text'];
                            $translatedTexts[$key] = $translatedText;
                        }
                    }

                    $content = "<?php\n\nreturn " . var_export($translatedTexts, true) . ";\n";
                    file_put_contents($outputFile, $content);
                    $createdFilesInfo[] = "Created file: $file for language pack: " . strtoupper($targetLanguage);

                }
            }
        }

        if (!empty($createdFilesInfo)) {
            return response("Переводы успешно созданы:\n" . implode("\n", $createdFilesInfo), 200);
        } else {
            return response("Все файлы для языкового пакета " . strtoupper($targetLanguage) . " уже существуют.", 200);
        }
    }



}
