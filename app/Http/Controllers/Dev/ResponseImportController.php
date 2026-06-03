<?php

namespace App\Http\Controllers\Dev;

use App\Enums\Resources\FullTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ResponseImportController extends Controller
{
    private const APP_ID   = 48;
    private const TYPE     = 'otzyvy';
    private const CATEGORY = '1179';

    private const UUID_SHORT_DESC = 'f4cb85e3-842b-4a82-a8df-fd326f162c46';
    private const UUID_DESC       = '5d2b4fb7-4008-455f-8bc3-e5a9939f5bc5';

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

        $data = [];

        foreach ($rows as $index => $row) {
            $el = json_decode($row->elements, true) ?? [];

            $data[] = [
                'title'      => $row->name,
                'slug'       => $row->alias,
                'template'   => FullTemplate::Response->value,
                'img'        => $row->introimage ?? null,
                'short_desc' => $el[self::UUID_SHORT_DESC][0]['value'] ?? null,
                'desc'       => $el[self::UUID_DESC][0]['value']       ?? null,
                'published'  => 1,
                'sorting'    => ($index + 1) * 10,
                'created_at' => $row->created ?? $now,
                'updated_at' => $now,
            ];
        }

        DB::table('responses')->upsert($data, ['slug'], [
            'title', 'template', 'img', 'short_desc', 'desc', 'published', 'sorting', 'created_at', 'updated_at',
        ]);

        return response()->json([
            'status'   => 'ok',
            'imported' => count($data),
            'sample'   => DB::table('responses')->orderByDesc('created_at')->limit(5)->get(['id', 'title', 'slug', 'created_at']),
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
