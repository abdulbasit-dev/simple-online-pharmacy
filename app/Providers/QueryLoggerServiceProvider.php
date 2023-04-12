<?php


/*
 * In order to use this service provider, you must add the following line to your 
 * file:.env
 * code: LOG_QUERY=true
 * 
 * This service provider will log all queries to the database in the file: storage/logs/query.log
 * 
 * note: This code is from this package (https://github.com/overtrue/laravel-query-logger), but instead of using the
 *      package, I decided to copy the code and modify it to my needs.
 */

namespace App\Providers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class QueryLoggerServiceProvider extends ServiceProvider
{

    public function boot()
    {
        //only log queries if the environment is local && the LOG_QUERY is set to true
        if (config('app.env') === 'local') {

            // false is the default value for get() method
            if (!$this->app['config']->get('logging.query.enabled', false)) {
                return;
            }

            $trigger =  $this->app['config']->get('logging.query.trigger');

            if (!empty($trigger) && $this->requestHasTrigger($trigger)) {
                return;
            }

            $this->app['events']->listen(QueryExecuted::class, function (QueryExecuted $query) {
                // the query contain this value (dd($query)) (https://github.com/laravel/framework/blob/9.x/src/Illuminate/Database/Events/QueryExecuted.php)

                if ($query->time < $this->app['config']->get('logging.query.slower_than', 0)) {
                    return;
                }

                $sqlWithPlaceHolder = str_replace(['%', '?', '%s%s'], ['%%', '%s', '?'], $query->sql);

                $bindings = $query->connection->prepareBindings($query->bindings);
                $pdo = $query->connection->getPdo();
                $realSql = $sqlWithPlaceHolder;
                $duration =  $this->formatDuration($query->time / 1000);

                if (count($bindings) > 0) {
                    $realSql = vsprintf($sqlWithPlaceHolder, array_map([$pdo, 'quote'], $bindings));
                }

                Log::channel(config('logging.query.channel'), config('logging.default'))
                    ->debug(sprintf(
                        '[%s] [%s] %s | %s: %s',
                        $query->connection->getDatabaseName(),
                        $duration,
                        $realSql,
                        request()->method(),
                        request()->getRequestUri()
                    ));
            });
        }
    }

    public function requestHasTrigger($trigger)
    {
        return false !== getenv($trigger) || \request()->hasHeader($trigger) || \request()->has($trigger) || \request()->hasCooke($trigger);
    }

    public function formatDuration($duration)
    {
        // the duration will be in seconds, beocuse we divide it by 1000 in the caller function
        if ($duration < 0.001) {
            //measured in microseconds
            return round($duration * 1000000) . 'μs';
        } elseif ($duration < 1) {
            //measured in milliseconds
            return round($duration * 1000, 2) . 'ms';
        }

        return round($duration, 2) . 's';
    }
}
