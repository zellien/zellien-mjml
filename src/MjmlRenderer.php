<?php

declare(strict_types=1);

namespace Zellien\Mjml;

use Zellien\Mjml\Exception\BadRequestException;
use Zellien\Mjml\Exception\ForbiddenException;
use Zellien\Mjml\Exception\UnauthorizedException;
use Zellien\Mjml\Exception\UnexpectedErrorException;

/**
 * Class MjmlRenderer
 *
 * @package Zellien\MjmlRenderer
 *
 * @psalm-type MjmlRendererConfig = array{application_id: non-empty-string, secret_key: non-empty-string}
 */
class MjmlRenderer {

    const API_URL = 'https://api.mjml.io/v1/render';

    /**
     * The Application ID acts as a username.
     *
     * @var non-empty-string
     */
    private string $applicationId;

    /**
     * Secret key act as a password.
     *
     * @var non-empty-string
     */
    private string $secretKey;

    /**
     * MjmlRenderer constructor.
     *
     * @psalm-param MjmlRendererConfig $config
     */
    public function __construct(array $config) {
        $this->applicationId = $config['application_id'];
        $this->secretKey = $config['secret_key'];
    }

    /**
     * Renders an MJML template using the API.
     *
     * @param string $mjml The MJML template to render.
     *
     * @psalm-param non-empty-string $mjml
     *
     * @return non-empty-string The rendered HTML.
     *
     * @throws BadRequestException If the request is invalid.
     * @throws UnauthorizedException If authentication fails.
     * @throws ForbiddenException If the request is unauthorized.
     * @throws UnexpectedErrorException If an unexpected error occurs.
     */
    public function render(string $mjml): string {
        $body = ['mjml' => $mjml];
        $ch = curl_init(self::API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_USERPWD, $this->applicationId . ':' . $this->secretKey);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        $response = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if (is_string($response)) {
            /** @var array{html: non-empty-string, message: non-empty-string} $result */
            $result = json_decode($response, true);

            return match ($httpCode) {
                200     => $result['html'],
                400     => throw new BadRequestException($result['message']),
                401     => throw new UnauthorizedException($result['message']),
                403     => throw new ForbiddenException($result['message']),
                default => throw new UnexpectedErrorException('An unexpected error has occurred')
            };
        }

        throw new UnexpectedErrorException('An unexpected error has occurred');
    }

}
