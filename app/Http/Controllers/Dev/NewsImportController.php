<?php

namespace App\Http\Controllers\Dev;

use App\Enums\Resources\FullTemplate;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Support\Facades\DB;

class NewsImportController extends Controller
{
    private const APP_ID   = 46;
    private const TYPE     = 'last-actions';
    private const CATEGORY = '1175';

    private const UUID_SHORT_DESC = '2f441995-200a-47d2-ae6c-a2e2d26348cc';
    private const UUID_DESC       = '4c501733-fe6b-40e5-a6df-7d6dacb023a3';

    public function preview()
    {
        $total = DB::table('t_zoo_item')
            ->where('application_id', self::APP_ID)
            ->where('type', self::TYPE)
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(`params`, '$.\"config.primary_category\"')) = ?", [self::CATEGORY])
            ->count();

        $rows = DB::table('t_zoo_item')
            ->where('application_id', self::APP_ID)
            ->where('type', self::TYPE)
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(`params`, '$.\"config.primary_category\"')) = ?", [self::CATEGORY])
            ->orderBy('id')
            ->limit(5)
            ->get();

        $sample = $rows->map(function ($row) {
            $el = json_decode($row->elements, true) ?? [];

            return [
                'alias'      => $row->alias,
                'name'       => $row->name,
                'img'        => $row->introimage ?? null,
                'short_desc' => mb_strimwidth($el[self::UUID_SHORT_DESC][0]['value'] ?? '(нет)', 0, 80, '…'),
                'desc'       => mb_strimwidth($el[self::UUID_DESC][0]['value']       ?? '(нет)', 0, 80, '…'),
                'created'    => $row->created ?? null,
            ];
        });

        return response()->json([
            'total'  => $total,
            'sample' => $sample,
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function import()
    {
        $rows = DB::table('t_zoo_item')
            ->where('application_id', self::APP_ID)
            ->where('type', self::TYPE)
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(`params`, '$.\"config.primary_category\"')) = ?", [self::CATEGORY])
            ->orderBy('id')
            ->get();

        $now = now()->toDateTimeString();

        News::truncate();

        $data = [];

        foreach ($rows as $index => $row) {
            $el = json_decode($row->elements, true) ?? [];

            $data[] = [
                'title'      => $row->name,
                'slug'       => $row->alias,
                'template'   => FullTemplate::News->value,
                'img'        => $row->introimage ?? null,
                'short_desc' => $el[self::UUID_SHORT_DESC][0]['value'] ?? null,
                'desc'       => $el[self::UUID_DESC][0]['value']       ?? null,
                'published'  => 1,
                'sorting'    => ($index + 1) * 10,
                'created_at' => $row->created ?? $now,
                'updated_at' => $now,
            ];
        }

        DB::table('news')->insert($data);

        return response()->json([
            'status'   => 'ok',
            'imported' => count($data),
            'sample'   => DB::table('news')->orderByDesc('created_at')->limit(5)->get(['id', 'title', 'slug', 'created_at']),
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
