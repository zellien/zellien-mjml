<?php

declare(strict_types=1);

namespace Zellien\MjmlTest;

use PHPUnit\Framework\TestCase;
use Zellien\Mjml\Exception\BadRequestException;
use Zellien\Mjml\Exception\ForbiddenException;
use Zellien\Mjml\Exception\UnauthorizedException;
use Zellien\Mjml\Exception\UnexpectedErrorException;
use Zellien\Mjml\MjmlRenderer;

class MjmlRendererTest extends TestCase {
    private const VALID_MJML = '<mjml><mj-body><mj-container><mj-section><mj-column><mj-text>Hello, World!</mj-text></mj-column></mj-section></mj-container></mj-body></mjml>';

    private array $config = [
        'application_id' => '',
        'secret_key'     => '',
    ];

    public function setUp(): void {
        parent::setUp();

        // Проверяем, есть ли ключи в переменных окружения
        $applicationId = $this->config['application_id'];
        $secretKey = $this->config['secret_key'];

        // Если ключи отсутствуют, сообщаем пользователю, что необходимо ввести их
        if (empty($applicationId) || empty($secretKey)) {
            $message = "\033[0;31mPlease enter your keys before running the tests!\033[0m" . PHP_EOL;
            fwrite(STDERR, $message . PHP_EOL);
            exit(1);
        }
    }

    /**
     * @throws UnauthorizedException
     * @throws BadRequestException
     * @throws UnexpectedErrorException
     * @throws ForbiddenException
     */
    public function testRenderValidMjml(): void {
        $renderer = new MjmlRenderer($this->config);
        $html = $renderer->render(self::VALID_MJML);

        $this->assertNotEmpty($html);
        // Add additional assertions for the expected rendered HTML
    }

    /**
     * @throws UnexpectedErrorException
     * @throws UnauthorizedException
     * @throws ForbiddenException
     */
    public function testRenderBadRequestException(): void {
        // Simulate a 400 Bad Request response from the API
        // You can use a test double or mock to override the behavior of the curl_exec() function
        // and make it return a response that triggers the exception
        $this->expectException(BadRequestException::class);

        $renderer = new MjmlRenderer($this->config);
        $renderer->render('invalid mjml');
    }

    /**
     * @throws UnexpectedErrorException
     * @throws ForbiddenException
     * @throws BadRequestException
     */
    public function testRenderUnauthorizedException(): void {
        // Simulate a 401 Unauthorized response from the API
        // You can use a test double or mock to override the behavior of the curl_exec() function
        // and make it return a response that triggers the exception
        $this->expectException(UnauthorizedException::class);

        $config = [
            'application_id' => '00000000-0000-0000-0000-000000000000',
            'secret_key'     => '00000000-0000-0000-0000-000000000000',
        ];

        $renderer = new MjmlRenderer($config);
        $renderer->render(self::VALID_MJML);
    }

}