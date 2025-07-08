<?php

// app/Core/BlockFactory.php
namespace App\Core;

abstract class BlockFactory
{
    protected string $name;
    protected array $args;

    public function __construct(string $name, array $args = [])
    {
        $this->name = $name;
        $this->args = wp_parse_args($args, [
            'render_template' => '',
            'category' => 'common',
            'icon' => 'admin-comments',
            'keywords' => [],
            'supports' => [],
        ]);
        $this->register();
    }

    protected function register(): void
    {
        $path = get_template_directory() . "/acf-blocks/{$this->name}/block.json";
        if (file_exists($path)) {
            register_block_type_from_metadata(
                get_template_directory() . "/acf-blocks/{$this->name}"
            );
        }
    }

    abstract public function render(array $block): string;
}

