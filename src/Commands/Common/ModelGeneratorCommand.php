<?php

namespace ArtisanApi\Velocity\Commands\Common;

use ArtisanApi\Velocity\Commands\BaseCommand;
use ArtisanApi\Velocity\Common\CommandData;
use ArtisanApi\Velocity\Generators\ModelGenerator;

class ModelGeneratorCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'velocity:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create model command';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->commandData = new CommandData($this, CommandData::$COMMAND_TYPE_API);
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();

        $modelGenerator = new ModelGenerator($this->commandData);
        $modelGenerator->generate();

        $this->performPostActions();
    }
}
