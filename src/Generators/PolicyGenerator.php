<?php

namespace ArtisanApi\Velocity\Generators;

use ArtisanApi\Velocity\Common\CommandData;
use ArtisanApi\Velocity\Utils\FileUtil;

class PolicyGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $fileName;

    /** @var string */
    private $basePolicy;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->pathPolicy;
        $this->fileName = $this->commandData->modelName.'Policy.php';
        $this->basePolicy = 'Policy.php';
    }

    public function generate()
    {
        if(!$this->checkBasePolicyExist())
        {
            $this->generateBasePolicy();
        }

        $templateData = get_template("policies.policy", 'velocity');
        $templateData = fill_template($this->commandData->dynamicVars, $templateData);
        FileUtil::createFile($this->path, $this->fileName, $templateData);
        $this->commandData->commandComment("\nPolicy created: ");
        $this->commandData->commandInfo($this->fileName);
    }

    private function generateBasePolicy()
    {
        $templateData = get_template('base_policy', 'velocity');
        $templateData = fill_template($this->commandData->dynamicVars, $templateData);
        FileUtil::createFile($this->commandData->config->pathPolicy, $this->basePolicy, $templateData);
        $this->commandData->commandComment("\nCreate Base Policy");
        $this->commandData->commandInfo($this->basePolicy);
    }

    /**
     * @return bool
     */
    private function checkBasePolicyExist()
    {
        return file_exists($this->commandData->config->pathPolicy.$this->basePolicy);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->fileName)) {
            $this->commandData->commandComment('Policy file deleted: '.$this->fileName);
        }
    }
}
