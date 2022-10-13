<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\OpenApi;
use ApiPlatform\OpenApi\Model;

final class ChangePasswordDecorator implements OpenApiFactoryInterface
{
    public function __construct(
        private OpenApiFactoryInterface $decorated
    ) {}

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();

        $schemas['User'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'readOnly' => false,
                ],
            ],
        ]);

        $pathItem = new Model\PathItem(
            ref: 'Change password',
            patch: new Model\Operation(
                operationId: 'postCredentialsItem',
                tags: ['User'],
                responses: [
                    '200' => [
                        'description' => 'Change user password',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/User',
                                ],
                            ],
                        ],
                    ],
                ],
                security: [],
            ),
        );
        $openApi->getPaths()->addPath('/users/change-password/{email}', $pathItem);

        return $openApi;
    }
}
