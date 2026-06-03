<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InlineEditController extends Controller
{
    protected array $modelMap = [
        'training' => \App\Models\Training::class,
        'news'     => \App\Models\News::class,
        'photo'    => \App\Models\Photo::class,
        'video'    => \App\Models\Video::class,
    ];

    protected array $allowedFields = [
        'desc', 'html', 'desc2', 'html2', 'short_desc',
        'ev_goals', 'ev_tasks', 'ev_methods', 'ev_results', 'ev_modules',
    ];

    // Поля с JSON-значением: значение декодируется перед сохранением
    protected array $jsonFields = [
        'ev_goals', 'ev_tasks', 'ev_methods', 'ev_results', 'ev_modules',
    ];

    public function update(Request $request): JsonResponse
    {
        if (! auth('moonshine')->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'model' => 'required|string',
            'id'    => 'required|integer|min:1',
            'field' => 'required|string',
            'value' => 'nullable|string',
        ]);

        $alias = $request->input('model');
        $field = $request->input('field');

        if (! array_key_exists($alias, $this->modelMap)) {
            return response()->json(['error' => 'Model not allowed'], 403);
        }

        if (! in_array($field, $this->allowedFields, true)) {
            return response()->json(['error' => 'Field not allowed'], 403);
        }

        $modelClass = $this->modelMap[$alias];
        $item       = $modelClass::findOrFail((int) $request->input('id'));

        if (in_array($field, $this->jsonFields, true)) {
            $decoded = json_decode((string) $request->input('value'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Некорректный JSON: ' . json_last_error_msg()], 422);
            }
            $item->$field = $decoded;
        } else {
            $item->$field = $request->input('value');
        }

        $item->save();

        return response()->json(['success' => true]);
    }
}
