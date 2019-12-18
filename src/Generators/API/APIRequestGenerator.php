<?php

namespace ArtisanApi\Velocity\Generators\API;

use Illuminate\Support\Str;
use ArtisanApi\Velocity\Common\CommandData;
use ArtisanApi\Velocity\Generators\BaseGenerator;
use ArtisanApi\Velocity\Generators\ModelGenerator;
use ArtisanApi\Velocity\Utils\FileUtil;

class APIRequestGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $createFileName;

    /** @var string */
    private $baseAPIRequest;

    /** @var string */
    private $updateFileName;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->pathApiRequest;
        $this->createFileName = 'Create'.$this->commandData->modelName.'APIRequest.php';
        $this->updateFileName = 'Update'.$this->commandData->modelName.'APIRequest.php';
        $this->baseAPIRequest = 'APIRequest.php';
    }

    public function generate()
    {
        if(!$this->checkBaseRequestExist())
        {
            $this->generateApiBaseRequest();
        }
        $this->generateCreateRequest();
        $this->generateUpdateRequest();
    }

    /**
     * @return bool
     */
    private function checkBaseRequestExist()
    {
        return file_exists($this->commandData->config->pathApiRequest.$this->baseAPIRequest);
    }

    private function generateApiBaseRequest()
    {
        $templateData = get_template('api_base_request', 'velocity');
        $templateData = fill_template($this->commandData->dynamicVars, $templateData);
        FileUtil::createFile($this->commandData->config->pathApiRequest, $this->baseAPIRequest, $templateData);
        $this->commandData->commandComment("\nCreate Base APIRequest");
        $this->commandData->commandInfo($this->baseAPIRequest);
    }

    private function generateCreateRequest()
    {
        $templateData = get_template('api.request.create_request', 'velocity');

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        $templateData = str_replace('$RULES$', implode(','.infy_nl_tab(1, 3), $this->generateRules()), $templateData);

        FileUtil::createFile($this->path, $this->createFileName, $templateData);

        $this->commandData->commandComment("\nCreate Request created: ");
        $this->commandData->commandInfo($this->createFileName);
    }

    private function generateUpdateRequest()
    {

        $templateData = get_template('api.request.update_request', 'velocity');

        $templateData = str_replace('$RULES$', implode(','.infy_nl_tab(1, 3), $this->generateRules()), $templateData);

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        FileUtil::createFile($this->path, $this->updateFileName, $templateData);

        $this->commandData->commandComment("\nUpdate Request created: ");
        $this->commandData->commandInfo($this->updateFileName);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->createFileName)) {
            $this->commandData->commandComment('Create API Request file deleted: '.$this->createFileName);
        }

        if ($this->rollbackFile($this->path, $this->updateFileName)) {
            $this->commandData->commandComment('Update API Request file deleted: '.$this->updateFileName);
        }
    }

    private function generateRules()
    {
        $dont_require_fields = config('artisanapi.velocity.options.hidden_fields', [])
            + config('artisanapi.velocity.options.excluded_fields', []);

        $rules = [];

        foreach ($this->commandData->fields as $field) {
            if (!$field->isPrimary && $field->isNotNull && empty($field->validations) &&
                !in_array($field->name, $dont_require_fields)) {
                $field->validations = 'required';
            }

            if (!empty($field->validations)) {
                if (Str::contains($field->validations, 'unique:')) {
                    $rule = explode('|', $field->validations);
                    // move unique rule to last
                    usort($rule, function ($record) {
                        return (Str::contains($record, 'unique:')) ? 1 : 0;
                    });
                    $field->validations = implode('|', $rule);
                }
                $rule = "'".$field->name."' => '".$field->validations."'";
                $rules[] = $rule;
            }
        }

        return $rules;
    }
}
