<?php

namespace Modules\System\Console\Traits;

use Modules\System\DotenvEditor;

trait CreateCommandInstanceTrait
{
    /**
     * The .env file editor instance.
     *
     * @var DotenvEditor
     */
    protected $editor;

    /**
     * Create a new command instance.
     *
     * @param DotenvEditor $editor The editor instance
     */
    public function __construct(DotenvEditor $editor)
    {
        parent::__construct();

        $this->editor = $editor;
    }

    /**
     * Execute the console command.
     *
     * This is alias of the method fire().
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->fire();
    }
}
