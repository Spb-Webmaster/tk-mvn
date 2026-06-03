<?php

namespace App\Http\Controllers\Dev;

use App\Enums\Resources\FullTemplate;
use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Support\Facades\DB;

class VideoImportController extends Controller
{
    private const APP_ID   = 46;
    private const TYPE     = 'media';
    private const CATEGORY = '1177';

    private const UUID_SHORT_DESC = '2a3f806c-099d-4304-85fb-f3e795ab2357';
    private const UUID_DESC       = 'c7d65be5-0dea-44f7-90d1-e800b730d1b5';

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

        Video::truncate();

        $data = [];

        foreach ($rows as $index => $row) {
            $el = json_decode($row->elements, true) ?? [];

            $data[] = [
                'title'      => $row->name,
                'slug'       => $row->alias,
                'template'   => FullTemplate::Video->value,
                'img'        => $row->introimage ?? null,
                'short_desc' => $el[self::UUID_SHORT_DESC][0]['value'] ?? null,
                'desc'       => $el[self::UUID_DESC][0]['value']       ?? null,
                'published'  => 1,
                'sorting'    => ($index + 1) * 10,
                'created_at' => $row->created ?? $now,
                'updated_at' => $now,
            ];
        }

        DB::table('videos')->insert($data);

        return response()->json([
            'status'   => 'ok',
            'imported' => count($data),
            'sample'   => DB::table('videos')->orderByDesc('created_at')->limit(5)->get(['id', 'title', 'slug', 'created_at']),
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
