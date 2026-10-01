<?php

namespace App\Support;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class Stats
{
    public const EXPORTS = [
        'daily'        => ['Tanggal', 'Pengunjung Unik', 'Page View', 'Klik WhatsApp'],
        'hours'        => ['Jam', 'Page View'],
        'pages'        => ['Halaman', 'Page View', 'Pengunjung'],
        'products'     => ['Produk', 'Dilihat', 'Klik WhatsApp', 'Lihat Cicilan'],
        'device_types' => ['Tipe Perangkat', 'Pengunjung'],
        'brands'       => ['Merek HP', 'Pengunjung'],
        'os'           => ['Sistem Operasi', 'Pengunjung'],
        'browsers'     => ['Browser', 'Pengunjung'],
        'sources'      => ['Sumber Trafik', 'Pengunjung'],
        'keywords'     => ['Kata Kunci', 'Jumlah Pencarian'],
        'visitors'     => ['Tanggal', 'Tipe', 'Merek', 'Model', 'OS', 'Browser', 'Sumber', 'Halaman Dilihat', 'Kunjungan Pertama', 'Kunjungan Terakhir'],
    ];

    private const SOURCES = [
        'direct' => 'Langsung', 'instagram' => 'Instagram', 'tiktok' => 'TikTok', 'facebook' => 'Facebook',
        'whatsapp' => 'WhatsApp', 'google' => 'Google', 'other' => 'Lainnya',
    ];

    private const TYPES = ['smartphone' => 'Smartphone', 'tablet' => 'Tablet', 'desktop' => 'Desktop'];

    private const LIST_LIMIT = 200;
    
    private const EVENT_SUMS = "
        SUM(CASE WHEN event = 'pageview' THEN 1 ELSE 0 END) AS pv,
        SUM(CASE WHEN event = 'wa_click' THEN 1 ELSE 0 END) AS wa,
        SUM(CASE WHEN event = 'search' THEN 1 ELSE 0 END) AS srch,
        SUM(CASE WHEN event = 'installment_view' THEN 1 ELSE 0 END) AS ins";

    public function __construct(public string $from, public string $to) {}

    private function days()
    {
        return DB::table('visitor_days')->whereBetween('visit_date', [$this->from, $this->to]);
    }

    private function views()
    {
        return DB::table('page_views')->whereBetween('visit_date', [$this->from, $this->to]);
    }

    public function summary(): array
    {
        $visitors = $this->days()->distinct()->count('visitor_id');
        $new      = DB::table('visitors')->whereBetween('first_seen_date', [$this->from, $this->to])->count();
        $c        = $this->views()->selectRaw(self::EVENT_SUMS)->first();
        $pv       = (int) ($c->pv ?? 0);

        return [
            'visitors'           => $visitors,
            'new_visitors'       => $new,
            'returning_visitors' => max(0, $visitors - $new),
            'page_views'         => $pv,
            'avg_pages'          => $visitors ? round($pv / $visitors, 1) : 0,
            'wa_clicks'          => (int) ($c->wa ?? 0),
            'searches'           => (int) ($c->srch ?? 0),
            'installment_views'  => (int) ($c->ins ?? 0),
            'online_now'         => DB::table('page_views')
                ->where('created_at', '>=', now()->subMinutes(5))
                ->distinct()->count('visitor_day_id'),
        ];
    }

    public function daily(): array
    {
        $vis = $this->days()->selectRaw('visit_date, COUNT(*) AS c')->groupBy('visit_date')->pluck('c', 'visit_date');
        $ev  = $this->views()->selectRaw('visit_date, '.self::EVENT_SUMS)->groupBy('visit_date')->get()->keyBy('visit_date');

        $rows = [];
        foreach (CarbonPeriod::create($this->from, $this->to) as $d) {
            $k = $d->toDateString();
            $rows[] = [
                'date'       => $k,
                'visitors'   => (int) ($vis[$k] ?? 0),
                'page_views' => (int) ($ev[$k]->pv ?? 0),
                'wa_clicks'  => (int) ($ev[$k]->wa ?? 0),
            ];
        }

        return $rows;
    }

    public function hours(): array
    {
        $m = $this->views()->where('event', 'pageview')
            ->selectRaw('visit_hour, COUNT(*) AS c')->groupBy('visit_hour')->pluck('c', 'visit_hour');

        $rows = [];
        for ($h = 0; $h < 24; $h++) {
            $rows[] = ['hour' => sprintf('%02d:00', $h), 'views' => (int) ($m[$h] ?? 0)];
        }

        return $rows;
    }

    // matriks 7 hari (Senin=0) x 24 jam
    public function heatmap(): array
    {
        $grid = array_fill(0, 7, array_fill(0, 24, 0));

        $rows = $this->views()->where('event', 'pageview')
            ->selectRaw('visit_date, visit_hour, COUNT(*) AS c')
            ->groupBy('visit_date', 'visit_hour')->get();

        foreach ($rows as $r) {
            $dow = Carbon::parse($r->visit_date)->dayOfWeekIso - 1;
            $grid[$dow][(int) $r->visit_hour] += (int) $r->c;
        }

        return $grid;
    }

    public function pages(): array
    {
        return $this->views()->where('event', 'pageview')
            ->selectRaw('path, COUNT(*) AS views, COUNT(DISTINCT visitor_day_id) AS visitors')
            ->groupBy('path')->orderByDesc('views')->limit(self::LIST_LIMIT)->get()
            ->map(fn ($r) => ['path' => $r->path, 'views' => (int) $r->views, 'visitors' => (int) $r->visitors])
            ->all();
    }

    public function products(): array
    {
        return DB::table('page_views as v')
            ->join('products as p', 'p.id', '=', 'v.product_id')
            ->whereBetween('v.visit_date', [$this->from, $this->to])
            ->selectRaw("p.name AS name,
                SUM(CASE WHEN v.event = 'pageview' THEN 1 ELSE 0 END) AS views,
                SUM(CASE WHEN v.event = 'wa_click' THEN 1 ELSE 0 END) AS wa_clicks,
                SUM(CASE WHEN v.event = 'installment_view' THEN 1 ELSE 0 END) AS installments")
            ->groupBy('p.id', 'p.name')
            ->orderByDesc('views')->orderByDesc('wa_clicks')
            ->limit(self::LIST_LIMIT)->get()
            ->map(fn ($r) => [
                'name'         => $r->name,
                'views'        => (int) $r->views,
                'wa_clicks'    => (int) $r->wa_clicks,
                'installments' => (int) $r->installments,
            ])->all();
    }

    private function breakdown(string $col, string $fallback, array $map = [], int $limit = 10): array
    {
        return $this->days()
            ->selectRaw("COALESCE($col, '$fallback') AS label, COUNT(*) AS total")
            ->groupBy('label')->orderByDesc('total')->limit($limit)->get()
            ->map(fn ($r) => ['label' => $map[$r->label] ?? $r->label, 'total' => (int) $r->total])
            ->all();
    }

    public function brands(): array { return $this->breakdown('device_brand', 'Tidak diketahui', [], self::LIST_LIMIT); }

    public function deviceTypes(): array { return $this->breakdown('device_type', 'Tidak diketahui', self::TYPES); }
    public function os(): array          { return $this->breakdown('os', 'Tidak diketahui'); }
    public function browsers(): array    { return $this->breakdown('browser', 'Tidak diketahui'); }
    public function sources(): array     { return $this->breakdown('referrer_source', 'direct', self::SOURCES); }

    public function keywords(): array
    {
        return $this->views()->where('event', 'search')->whereNotNull('search_keyword')
            ->selectRaw('LOWER(search_keyword) AS keyword, COUNT(*) AS total')
            ->groupBy('keyword')->orderByDesc('total')->limit(self::LIST_LIMIT)->get()
            ->map(fn ($r) => ['keyword' => $r->keyword, 'total' => (int) $r->total])
            ->all();
    }

    public function visitors(int $limit = 50): array
    {
        return $this->days()->orderByDesc('last_visit_at')->limit(self::LIST_LIMIT)
            ->get(['visit_date', 'device_type', 'device_brand', 'device_model', 'os', 'browser',
                   'referrer_source', 'page_views_count', 'first_visit_at', 'last_visit_at'])
            ->map(function ($r) {
                $r = (array) $r;
                $r['device_type']     = self::TYPES[$r['device_type']] ?? $r['device_type'];
                $r['referrer_source'] = self::SOURCES[$r['referrer_source'] ?? 'direct'] ?? $r['referrer_source'];

                return $r;
            })->all();
    }

    public function export(string $key): ?array
    {
        if (! isset(self::EXPORTS[$key])) {
            return null;
        }

        $rows = match ($key) {
            'daily'        => $this->daily(),
            'hours'        => $this->hours(),
            'pages'        => $this->pages(),
            'products'     => $this->products(),
            'device_types' => $this->deviceTypes(),
            'brands'       => $this->brands(),
            'os'           => $this->os(),
            'browsers'     => $this->browsers(),
            'sources'      => $this->sources(),
            'keywords'     => $this->keywords(),
            'visitors'     => $this->visitors(5000),
        };

        return ['headers' => self::EXPORTS[$key], 'rows' => array_map('array_values', $rows)];
    }
}