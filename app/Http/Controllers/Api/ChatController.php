<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|max:5000',
            'history' => 'sometimes|array',
            'history.*.role' => 'required_with:history|in:user,assistant,model',
            'history.*.text' => 'required_with:history|string',
        ]);

        $provider = env('AI_PROVIDER', 'openai');

        try {
            if ($provider === 'openai') {
                return $this->callOpenAI($data);
            }
            // مكان توسعة مستقبلية (Gemini…)
            return response()->json(['error' => 'Unsupported provider'], 422);
        } catch (\Throwable $e) {
            Log::error('AI error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'AI service failed'], 500);
        }
    }

    protected function callOpenAI(array $data)
    {
        $apiKey = env('OPENAI_API_KEY');
        $model  = env('OPENAI_MODEL', 'gpt-4o-mini');

        if (!$apiKey) {
            return response()->json(['error' => 'OPENAI_API_KEY missing'], 500);
        }

        $client = new Client([
            'base_uri' => 'https://api.openai.com/',
            'timeout'  => 30,
        ]);

        // history -> messages
        $messages = [];
        if (!empty($data['history'])) {
            foreach ($data['history'] as $turn) {
                $role = $turn['role'] === 'user' ? 'user' : 'assistant';
                $messages[] = ['role' => $role, 'content' => $turn['text']];
            }
        }
        $messages[] = ['role' => 'system', 'content' => 'أنت مساعد لموقع مقالات. أجب باختصار ووضوح.'];
        $messages[] = ['role' => 'user',   'content' => $data['message']];

        $res = $client->post('v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer '.$apiKey,
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'model' => $model,
                'messages' => $messages,
                'temperature' => 0.6,
            ],
        ]);

        $body = json_decode((string) $res->getBody(), true);
        $text = $body['choices'][0]['message']['content'] ?? null;

        return $text
            ? response()->json(['reply' => $text, 'model' => $model, 'id' => (string) Str::uuid()])
            : response()->json(['error' => 'No text from OpenAI'], 500);
    }
}
