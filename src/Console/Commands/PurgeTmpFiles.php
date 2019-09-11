<?php

namespace Novius\NovaVisualComposer\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class PurgeTmpFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nova-visual-composer:purge-tmp-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old tmp files.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (config('app.env') === 'production') {
            if (!$this->confirm('Production env !! Do you wish to continue ?')) {
                $this->error('Bye.');
            }
        }

        $files = collect(Storage::disk(config('nova-visual-composer.upload_disk'))->allFiles(
            config('nova-visual-composer.tmp_files_path')
        ))->filter(function ($file) {
            $lastModified = Storage::disk(config('nova-visual-composer.upload_disk'))->lastModified(
                $file
            );

            if ((Carbon::now()->timestamp - $lastModified) > config('nova-visual-composer.seconds_before_purge_tmp_files')) {
                return true;
            }

            return false;
        });

        if (!count($files)) {
            $this->info('0 file to purge.');

            return;
        }

        $this->info(sprintf('%d file(s) to purge.', count($files)));

        $bar = $this->output->createProgressBar(count($files));
        $bar->start();
        foreach ($files as $file) {
            Storage::disk(config('nova-visual-composer.upload_disk'))->delete($file);
            $bar->advance();
        }

        $bar->finish();
        $this->info('Successfully purge tmp files.');
    }
}
