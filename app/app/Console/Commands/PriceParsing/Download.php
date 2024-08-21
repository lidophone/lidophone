<?php

namespace App\Console\Commands\PriceParsing;

use App\Console\Commands\BaseCommand;

class Download extends BaseCommand
{
    protected $signature = 'app:price-parsing:download {developer}';

    public const DEVELOPER_KVS = 'kvs';
    public const DEVELOPER_PIK_MOSCOW = 'pik-moscow';
    public const DEVELOPER_PIK_SPB = 'pik-spb';

    private const URL_KVS = 'https://b2b.kvsspb.ru/xml/DumpFileYandexNovostroiki.xml';
    private const URL_PIK_MOSCOW = 'https://exchange.novostroy-m.ru/exchange/export/pik_yaxml?access_token=01cb7262ea7cbfe5';
    private const URL_PIK_SPB = 'https://exchange.novostroy-spb.ru/exchange/export/pik_yaxml_spb?access_token=d70e27aa4cf21ed0';

    public function handle(): void
    {
        $developer = $this->argument('developer');
        $url = match ($developer) {
            self::DEVELOPER_KVS => self::URL_KVS,
            self::DEVELOPER_PIK_MOSCOW => self::URL_PIK_MOSCOW,
            self::DEVELOPER_PIK_SPB => self::URL_PIK_SPB,
        };
        exec('curl -o ' . self::getFilename($developer) . ' ' . $url);
        $this->printCommandCompletionMessage();
    }

    public static function getFilename(string $developer): string
    {
        return storage_path('app/price-parsing/' . $developer . '.xml');
    }
}
