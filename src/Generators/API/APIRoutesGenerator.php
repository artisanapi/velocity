<?php

namespace ArtisanApi\Velocity\Generators\API;

use Illuminate\Support\Str;
use ArtisanApi\Velocity\Common\CommandData;
use ArtisanApi\Velocity\Generators\BaseGenerator;

class APIRoutesGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $routeContents;

    /** @var string */
    private $routesTemplate;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->pathApiRoutes;

        $this->routeContents = file_get_contents($this->path);

        if (!empty($this->commandData->config->prefixes['route'])) {
            $routesTemplate = get_template('api.routes.prefix_routes', 'velocity');
        } else {
            $routesTemplate = get_template('api.routes.routes', 'velocity');
        }

        $this->routesTemplate = fill_template($this->commandData->dynamicVars, $routesTemplate);
    }

    public function generate()
    {
        $this->routeContents .= "\n\n".$this->routesTemplate;
        $existingRouteContents = file_get_contents($this->path);
        if (Str::contains($existingRouteContents, "Route::apiResource('".$this->commandData->config->mSnakePlural."',")) {
            $this->commandData->commandObj->info('Menu '.$this->commandData->config->mPlural.'is already exists, Skipping Adjustment.');

            return;
        }

        file_put_contents($this->path, $this->routeContents);

        $this->commandData->commandComment("\n".$this->commandData->config->mCamelPlural.' api routes added.');
    }

    public function rollback()
    {
        if (Str::contains($this->routeContents, $this->routesTemplate)) {
            $this->routeContents = str_replace($this->routesTemplate, '', $this->routeContents);
            file_put_contents($this->path, $this->routeContents);
            $this->commandData->commandComment('api routes deleted');
        }
    }
}
