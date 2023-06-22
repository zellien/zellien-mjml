<?php

declare(strict_types=1);

namespace Zellien\Mjml;

/**
 * Class ConfigProvider
 *
 * @package Zellien\Mjml
 */
class ConfigProvider {

    /**
     * Return configuration for this component.
     *
     * @return array
     */
    public function __invoke(): array {
        return [
            'dependencies' => $this->getDependencyConfig(),
        ];
    }

    /**
     * Return dependency mappings for this component.
     *
     * @return array{
     *     aliases: array<string, string>,
     *     factories: array<string, string>
     * }
     */
    public function getDependencyConfig(): array {
        return [
            'aliases'   => [
                'MjmlRenderer' => MjmlRenderer::class,
            ],
            'factories' => [
                MjmlRenderer::class => MjmlRendererFactory::class,
            ],
        ];
    }

}
