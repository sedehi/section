<?php

namespace Sedehi\Section;

use Symfony\Component\Console\Input\InputOption;

trait SectionOption
{
    protected function getOptions(){

        $options = parent::getOptions();
        $options = array_merge($options, [
            ['section', 's', InputOption::VALUE_OPTIONAL, 'The name of the section'],
        ]);

        return $options;
    }
}