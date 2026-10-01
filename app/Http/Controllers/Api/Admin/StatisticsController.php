<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Support\Stats;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $s = $this->stats($request);

        return response()->json([
            'range'        => ['from' => $s->from, 'to' => $s->to],
            'summary'      => $s->summary(),
            'daily'        => $s->daily(),
            'hours'        => array_column($s->hours(), 'views'),
            'heatmap'      => $s->heatmap(),
            'pages'        => $s->pages(),
            'products'     => $s->products(),
            'device_types' => $s->deviceTypes(),
            'brands'       => $s->brands(),
            'os'           => $s->os(),
            'browsers'     => $s->browsers(),
            'sources'      => $s->sources(),
            'keywords'     => $s->keywords(),
            'recent'       => $s->visitors(500),
        ]);
    }

    public function export(Request $request, string $type)
    {
        $s    = $this->stats($request);
        $data = $s->export($type) ?? abort(404);

        $format = $request->query('format') === 'xls' ? 'xls' : 'csv';
        $name   = "statistik-{$type}-{$s->from}_{$s->to}.{$format}";

        if ($format === 'xls') {
            $html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel"><head><meta charset="UTF-8"></head><body>'
                .'<table border="1"><thead><tr>';
            foreach ($data['headers'] as $h) {
                $html .= '<th style="background:#e0e7ff">'.e($h).'</th>';
            }
            $html .= '</tr></thead><tbody>';
            foreach ($data['rows'] as $row) {
                $html .= '<tr>';
                foreach ($row as $cell) {
                    $html .= '<td>'.e($this->clean($cell)).'</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody></table></body></html>';

            return response($html, 200, [
                'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="'.$name.'"',
            ]);
        }

        return response()->streamDownload(function () use ($data) {
            echo "\xEF\xBB\xBF"; // BOM agar Excel membaca UTF-8
            $out = fopen('php://output', 'w');
            // titik koma = pemisah default Excel dengan regional Indonesia
            fputcsv($out, $data['headers'], ';', '"', '\\');
            foreach ($data['rows'] as $row) {
                fputcsv($out, array_map(fn ($c) => $this->clean($c), $row), ';', '"', '\\');
            }
            fclose($out);
        }, $name, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function stats(Request $request): Stats
    {
        $v = $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to'   => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
        ]);

        $to   = $v['to'] ?? today()->toDateString();
        $from = $v['from'] ?? today()->subDays(29)->toDateString();

        if (Carbon::parse($from)->addDays(365)->lt(Carbon::parse($to))) {
            abort(422, 'Rentang maksimal 366 hari.');
        }

        return new Stats($from, $to);
    }

    // cegah CSV/formula injection (kata kunci pencarian berasal dari publik)
    private function clean(mixed $v): mixed
    {
        if ($v === null) {
            return '';
        }

        return is_string($v) && preg_match('/^[=+\-@\t\r]/', $v) ? "'".$v : $v;
    }
}