<?php

declare(strict_types=1);

namespace Zellien\Mjml;

use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class MjmlRendererFactory
 *
 * @package Zellien\Mjml
 */
class MjmlRendererFactory {
    /**
     * @param ContainerInterface $container
     *
     * @return MjmlRenderer
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MjmlRenderer {
        $config = $container->get('config')['mjml'] ?? null;

        if (!is_array($config)) {
            throw new InvalidArgumentException('Invalid or missing configuration for "mjml"');
        }

        $applicationId = $config['application_id'] ?? null;
        $secretKey = $config['secret_key'] ?? null;

        if (!is_string($applicationId) || $applicationId === '') {
            throw new InvalidArgumentException('Invalid or missing application ID');
        }

        if (!is_string($secretKey) || $secretKey === '') {
            throw new InvalidArgumentException('Invalid or missing secret key');
        }

        return new MjmlRenderer([
            'application_id' => $applicationId,
            'secret_key'     => $secretKey,
        ]);
    }

}
