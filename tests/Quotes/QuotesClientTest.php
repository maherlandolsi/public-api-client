<?php

namespace ArrowSphere\PublicApiClient\Tests\Quotes;

use ArrowSphere\PublicApiClient\Exception\NotFoundException;
use ArrowSphere\PublicApiClient\Exception\PublicApiClientException;
use ArrowSphere\PublicApiClient\Quotes\QuotesClient;
use ArrowSphere\PublicApiClient\Quotes\Request\CreateQuote;
use ArrowSphere\PublicApiClient\Tests\AbstractClientTest;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

/**
 * Class QuotesClientTest
 *
 * @property QuotesClient $client
 */
class QuotesClientTest extends AbstractClientTest
{
    protected const MOCKED_CLIENT_CLASS = QuotesClient::class;

    /**
     * @var QuotesClient
     */
    protected $client;

    /**
     * @return CreateQuote
     */
    public function createPayload(): CreateQuote
    {
        return new CreateQuote([
            'items' => [
                [
                  'arrowSpherePriceBandSku' => 'MSCSP_CFQ7TTC0LCHC-0002_FR_EUR_1_720_8640',
                  'quantity' => 2,
                  'prices' => [
                    'customer' => [
                      'rate' => [
                        'rateType' => 'discount',
                        'value' => 0.05
                      ],
                      'value' => 0
                    ],
                  ]
                ]
            ],
            'customer' => [
                'reference' => 'XSP4533'
            ],
        ]);
    }

    /**
     * @return array
     */
    private function createResponse(): array
    {
        return [
            'status' => 200,
            'data' => [
                'status' => 'In progress',
                'reference' => 'XSPQ1345631',
                'link' => '/api/quotes/XSPQ1345631'
            ]
        ];
    }

    /**
     * @throws NotFoundException
     * @throws GuzzleException
     * @throws PublicApiClientException
     */
    public function testCreate(): void
    {
        $quotePayload = $this->createPayload();
        $createResponse = $this->createResponse();

        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->with('post', 'https://www.test.com/quotes')
            ->willReturn(new Response(200, [], json_encode($createResponse)));

        $result = $this->client->create($quotePayload);

        self::assertEquals($createResponse['data']['link'], $result->getLink());
        self::assertEquals($createResponse['data']['reference'], $result->getReference());
        self::assertEquals($createResponse['data']['status'], $result->getStatus());
    }
}