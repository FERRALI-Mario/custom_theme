<?php

namespace AcfBlocks\NumberCounter;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('number-counter');
    }

    public function getTitle(): string
    {
        return 'Number Counter';
    }

    public function getDescription(): string
    {
        return 'Un bloc avec des compteurs animés.';
    }

    public function getCategory(): string
    {
        return 'widgets';
    }

    public function getKeywords(): array
    {
        return ['counter', 'number', 'statistic', 'animated'];
    }
}
