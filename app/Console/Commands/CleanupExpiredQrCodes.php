<?php

namespace App\Console\Commands;

use App\Services\QrCodeService;
use Illuminate\Console\Command;

class CleanupExpiredQrCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qrcode:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup expired QR codes and their files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting QR code cleanup...');

        $deletedCount = QrCodeService::cleanupExpired();

        $this->info("✓ Cleaned up {$deletedCount} expired QR codes");

        return Command::SUCCESS;
    }
}
