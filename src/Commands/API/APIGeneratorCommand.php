<?php

namespace ArtisanApi\Velocity\Commands\API;

use ArtisanApi\Velocity\Commands\BaseCommand;
use ArtisanApi\Velocity\Common\CommandData;
use ArtisanApi\Velocity\Generators\API\APIControllerGenerator;
use ArtisanApi\Velocity\Generators\API\APIRequestGenerator;
use ArtisanApi\Velocity\Generators\API\APIResourceGenerator;
use ArtisanApi\Velocity\Generators\API\APIRoutesGenerator;
use ArtisanApi\Velocity\Generators\API\APITestGenerator;
use ArtisanApi\Velocity\Generators\PolicyGenerator;

class APIGeneratorCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'velocity:api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a full CRUD API for given model';

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

        $this->generateCommonItems();

        $this->generateAPIItems();

        $this->performPostActionsWithMigration();
    }

    public function generateAPIItems()
    {
        (new PolicyGenerator($this->commandData))->generate();
        (new APIResourceGenerator($this->commandData))->generate();

        if (!$this->isSkip('requests') and !$this->isSkip('api_requests')) {
            $requestGenerator = new APIRequestGenerator($this->commandData);
            $requestGenerator->generate();
        }

        if (!$this->isSkip('controllers') and !$this->isSkip('api_controller')) {
            $controllerGenerator = new APIControllerGenerator($this->commandData);
            $controllerGenerator->generate();
        }

        if (!$this->isSkip('routes') and !$this->isSkip('api_routes')) {
            $routesGenerator = new APIRoutesGenerator($this->commandData);
            $routesGenerator->generate();
        }

        if (!$this->isSkip('tests')) {
            $apiTestGenerator = new APITestGenerator($this->commandData);
            $apiTestGenerator->generate();
        }
    }
}
