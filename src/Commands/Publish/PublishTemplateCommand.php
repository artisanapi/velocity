<?php

namespace ArtisanApi\Velocity\Commands\Publish;

class PublishTemplateCommand extends PublishBaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'velocity.publish:templates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes api generator templates.';

    private $templatesDir;

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $this->templatesDir = config(
            'artisanapi.velocity.path.templates_dir',
            resource_path('artisanapi/velocity-templates/')
        );

        if ($this->publishGeneratorTemplates()) {
            // $this->publishSwaggerTemplates();
        }
    }

    /**
     * Publishes templates.
     */
    public function publishGeneratorTemplates()
    {
        $templatesPath = __DIR__.'/../../../templates';

        return $this->publishDirectory($templatesPath, $this->templatesDir, 'velocity-templates');
    }

    /**
     * Publishes swagger templates.
     */
    // public function publishSwaggerTemplates()
    // {
    //     $templatesPath = base_path('vendor/artisanapi/swagger-generator/templates');
    //
    //     return $this->publishDirectory($templatesPath, $this->templatesDir, 'swagger-generator', true);
    // }

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }
}
