<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Tracker
{
    private const EVENTS = ['pageview', 'wa_click', 'search', 'installment_view'];
    private const BOT    = '/bot|crawl|spider|slurp|preview|monitor|curl|wget|python|headless|lighthouse|pingdom|facebookexternalhit/i';

    public static function record(Request $request): void
    {
        $ua    = (string) $request->userAgent();
        $event = (string) $request->input('event', 'pageview');

        if ($ua === '' || preg_match(self::BOT, $ua) || ! in_array($event, self::EVENTS, true) || Auth::check()) {
            return;
        }

        $path = '/'.ltrim((string) parse_url((string) $request->input('path', '/'), PHP_URL_PATH), '/');
        if (str_starts_with($path, '/admin') || $path === '/login') {
            return;
        }
        $path = mb_substr($path, 0, 255);

        $keyword = null;
        if ($event === 'search') {
            $keyword = mb_substr(trim((string) $request->input('keyword')), 0, 100);
            if (mb_strlen($keyword) < 2) {
                return;
            }
        }

        // produk diambil dari URL /product/{slug}
        $productId = null;
        if (preg_match('#^/product/([^/]+)#', $path, $m)) {
            $productId = DB::table('products')->where('slug', urldecode($m[1]))->value('id');
        }

        $now    = now();
        $today  = $now->toDateString();
        $hash   = hash_hmac('sha256', (string) $request->ip(), (string) config('app.key'));
        $device = UserAgent::parse($ua);

        $ref     = mb_substr((string) $request->input('referrer'), 0, 500);
        $refHost = $ref !== '' ? mb_substr((string) parse_url($ref, PHP_URL_HOST), 0, 255) : null;
        $source  = UserAgent::referrer($ref, $request->getHost());

        $country = strtoupper((string) $request->header('CF-IPCountry'));
        $country = preg_match('/^[A-Z]{2}$/', $country) && ! in_array($country, ['XX', 'T1'], true) ? $country : null;

        DB::transaction(function () use ($hash, $today, $now, $device, $source, $refHost, $country, $event, $path, $keyword, $productId) {
            $newVisitor = DB::table('visitors')->insertOrIgnore([
                'ip_hash'         => $hash,
                'first_seen_date' => $today,
                'last_seen_date'  => $today,
                'total_visit_days' => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]) === 1;

            $visitorId = DB::table('visitors')->where('ip_hash', $hash)->value('id');

            $newDay = DB::table('visitor_days')->insertOrIgnore($device + [
                'visitor_id'       => $visitorId,
                'visit_date'       => $today,
                'country_code'     => $country,
                'referrer_source'  => $source,
                'referrer_host'    => $refHost,
                'page_views_count' => 0,
                'first_visit_at'   => $now,
                'last_visit_at'    => $now,
                'created_at'       => $now,
                'updated_at'       => $now,
            ]) === 1;

            $dayId = DB::table('visitor_days')
                ->where('visitor_id', $visitorId)
                ->where('visit_date', $today)
                ->value('id');

            if ($newDay && ! $newVisitor) {
                DB::table('visitors')->where('id', $visitorId)->update([
                    'total_visit_days' => DB::raw('total_visit_days + 1'),
                    'last_seen_date'   => $today,
                    'updated_at'       => $now,
                ]);
            }

            $dayUpdate = ['last_visit_at' => $now, 'updated_at' => $now];
            if ($event === 'pageview') {
                $dayUpdate['page_views_count'] = DB::raw('page_views_count + 1');
            }
            DB::table('visitor_days')->where('id', $dayId)->update($dayUpdate);

            DB::table('page_views')->insert([
                'visitor_day_id' => $dayId,
                'event'          => $event,
                'path'           => $path,
                'product_id'     => $productId,
                'search_keyword' => $keyword,
                'visit_date'     => $today,
                'visit_hour'     => $now->hour,
                'created_at'     => $now,
            ]);
        });
    }
}