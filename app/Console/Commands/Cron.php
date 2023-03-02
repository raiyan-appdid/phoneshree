<?php

namespace App\Console\Commands;

use App\Services\ActiveBannerAdsService;
use App\Services\FeaturedProductService;
use Illuminate\Console\Command;

class Cron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:manageExpiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will manage all expiry related queries';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ActiveBannerAdsService::activateBannerAds();
        ActiveBannerAdsService::deleteBuyerPhoneOnExpiry();
        FeaturedProductService::activateFeaturedProduct();
        \Log::info('Cron ran at: ' . now()->toString());
        return $this->info('Command run successfully');
    }
}
