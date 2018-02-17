<?php

namespace Alexusmai\EasySettings\Commands;

use Alexusmai\EasySettings\Models\EasySettings;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class EasySettingsCreateSeed extends Command
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    protected $model;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'esettings:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create seeder for Easy Settings package';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files, EasySettings $model)
    {
        parent::__construct();

        $this->files = $files;
        $this->model = $model;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        // get all data from db
        $data = $this->model->all();

        if ( !$data->isEmpty() ) {

            $str = '';

            foreach($data as $item) {

                // get template
                $str .= $this->tableSchema();

                $str = str_replace([
                    '{name}',
                    '{title}',
                    '{schema}',
                    '{data}',
                    '{created_at}'
                ], [
                    $item->name,
                    $item->title,
                    json_encode($item->schema),
                    json_encode($item->data),
                    $item->created_at
                ], $str);

            }

            // get stub
            $stub = $this->files->get(__DIR__.'/seeder.stub');

            $this->files->put(
                $this->getPath(),
                str_replace('{SEED}', $str, $stub)
            );

            $this->info('EasySettingsSeeder created!');
        } else {
            // no data in db
            $this->error('No data in DB!');
        }
    }

    /**
     * Get the destination class path.
     *
     * @return string
     */
    protected function getPath()
    {
        return database_path().'/seeds/EasySettingsSeeder.php';
    }


    /**
     * Table template
     * @return string
     */
    protected function tableSchema()
    {
        return <<<EOT
DB::table('easy_settings')->insert([
            'name'          => '{name}',
            'title'         => '{title}',
            'schema'        => '{schema}',
            'data'          => '{data}',
            'created_at'    => Carbon::createFromFormat('Y-m-d H:i:s', '{created_at}')
        ]);
        
        
EOT;
    }
}
