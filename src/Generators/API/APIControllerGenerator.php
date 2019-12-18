<?php

namespace ArtisanApi\Velocity\Generators\API;

use ArtisanApi\Velocity\Common\CommandData;
use ArtisanApi\Velocity\Generators\BaseGenerator;
use ArtisanApi\Velocity\Utils\FileUtil;

class APIControllerGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $fileName;

    /** @var string */
    private $baseApiController;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->pathApiController;
        $this->fileName = $this->commandData->modelName.'APIController.php';
        $this->baseApiController = 'APIController.php';
    }

    public function generate()
    {
        if(!$this->checkApiBaseControllerExist())
        {
            $this->generateApiBaseController();
        }

        $templateData = get_template("api.controller.model_api_controller", 'velocity');
        $templateData = fill_template($this->commandData->dynamicVars, $templateData);
        $templateData = $this->fillDocs($templateData);
        FileUtil::createFile($this->path, $this->fileName, $templateData);
        $this->commandData->commandComment("\nAPI Controller created: ");
        $this->commandData->commandInfo($this->fileName);
    }

    private function generateApiBaseController()
    {
        $templateData = get_template('api_base_controller', 'velocity');
        $templateData = fill_template($this->commandData->dynamicVars, $templateData);
        FileUtil::createFile($this->commandData->config->pathApiController, $this->baseApiController, $templateData);
        $this->commandData->commandComment("\nCreate Base APIController");
        $this->commandData->commandInfo($this->baseApiController);
    }

    /**
     * @return bool
     */
    private function checkApiBaseControllerExist()
    {
        return file_exists($this->commandData->config->pathApiController.$this->baseApiController);
    }


    private function fillDocs($templateData)
    {
        $methods = ['controller', 'index', 'store', 'show', 'update', 'destroy'];

        if ($this->commandData->getAddOn('swagger')) {
            $templatePrefix = 'controller_docs';
            $templateType = 'swagger-generator';
        } else {
            $templatePrefix = 'api.docs.controller';
            $templateType = 'velocity';
        }

        foreach ($methods as $method) {
            $key = '$DOC_'.strtoupper($method).'$';
            $docTemplate = get_template($templatePrefix.'.'.$method, $templateType);
            $docTemplate = fill_template($this->commandData->dynamicVars, $docTemplate);
            $templateData = str_replace($key, $docTemplate, $templateData);
        }

        return $templateData;
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->fileName)) {
            $this->commandData->commandComment('API Controller file deleted: '.$this->fileName);
        }
    }
}
