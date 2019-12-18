<?php

namespace ArtisanApi\Velocity\Generators\API;

use ArtisanApi\Velocity\Common\CommandData;
use ArtisanApi\Velocity\Generators\BaseGenerator;
use ArtisanApi\Velocity\Utils\FileUtil;

class APIResourceGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $fileName;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->pathApiResource;
        $this->fileName = $this->commandData->modelName.'Resource.php';
    }

    public function generate()
    {
        $templateData = get_template("api.resources.api_resource", 'velocity');

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        FileUtil::createFile($this->path, $this->fileName, $templateData);

        $this->commandData->commandComment("\nAPI Resource created: ");
        $this->commandData->commandInfo($this->fileName);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->fileName)) {
            $this->commandData->commandComment('API Resource file deleted: '.$this->fileName);
        }
    }
}
