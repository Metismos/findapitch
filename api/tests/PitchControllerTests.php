<?php

namespace App\Tests;

use App\Entity\Pitch;
use App\Tests\OurWebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

class PitchControllerTests extends OurWebTestCase
{
    /**
     * 
     */
    public function testListSuccess(): array
    {
        $this->client->request('GET', "/api/v1/pitches");

        $actual = $this->client->getResponse();
        $content = json_decode($actual->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $actual->getStatusCode());
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $actual);
        $this->assertNotEmpty($content['data']);
        
        return $content;
    }

    /**
     * @return string
     * 
     * @depends testListSuccess
     */
    public function testRetrieveSuccess(array $pitches): array
    {
        $pitchId = $pitches['data'][0]['id'];

        $this->client->request('GET', "/api/v1/pitches/$pitchId");

        $actual = $this->client->getResponse();
        $content = json_decode($actual->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $actual->getStatusCode());
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $actual);
        $this->assertNotEmpty($content['data']);
        
        return $content;
    }

    /**
     * @depends testRetrieveSuccess
     */
    public function testListSlotsSuccess(array $pitch): void
    {
        $pitchId = $pitch['data']['id'];
        
        $this->client->request('GET', "/api/v1/pitches/$pitchId/slots");

        $actual = $this->client->getResponse();
        $content = json_decode($actual->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $actual->getStatusCode());
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $actual);
        $this->assertNotEmpty($content['data']);
    }

    /**
     * @depends testRetrieveSuccess
     */
    public function testAddSlotsSuccess($pitch): void
    {
        $pitchId = $pitch['data']['id'];

        $this->client->request(
            'POST',
            "/api/v1/pitches/$pitchId/slots",
            [],
            [], 
            ['Content_Type' => 'application/json'], 
            '[{"starts":"2016-11-04T13:00:00+00:00","ends":"2016-11-04T14:00:00+00:00","price":66,"currency":"GBP","isAvailable":true},{"starts":"2016-11-04T14:00:00+00:00","ends":"2016-11-04T15:00:00+00:00","price":66,"currency":"EUR","isAvailable":true}]'
        );

        $actual = $this->client->getResponse();
        $content = json_decode($actual->getContent(), true);

        $this->assertEquals(Response::HTTP_CREATED, $actual->getStatusCode());
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $actual);
        $this->assertNotEmpty($content['data']);
    }

    /**
     * 
     */
    public function testRetrieveNotFoundFailure(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->client->request('GET', "/api/v1/pitches/xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx");

        $actual = $this->client->getResponse();
    }

    /**
     * 
     */
    public function testListSlotsPitchNotFoundFailure(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->client->request('GET', "/api/v1/pitches/xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx/slots");

        $actual = $this->client->getResponse();
    }

    /**
     * @depends testRetrieveSuccess
     */
    public function testAddSlotsPreconditionFailedFailure($pitch): void
    {
        $pitchId = $pitch['data']['id'];

        $this->expectException(PreconditionFailedHttpException::class);

        $this->client->request('GET', "/api/v1/pitches/$pitchId/slots");

        $this->client->request(
            'POST',
            "/api/v1/pitches/$pitchId/slots",
            [],
            [], 
            ['Content_Type' => 'application/json'], 
            '[{"starts":"2016-11-04T10:00:00+00:00","ends":"2016-11-04T11:00:00+00:00","price":66,"currency":"GBP","isAvailable":true},{"starts":"2016-11-04T14:00:00+00:00","ends":"2016-11-04T15:00:00+00:00","price":66,"currency":"EUR","isAvailable":true}]'
        );

        $actual = $this->client->getResponse();
    }


    /**
     * @depends testRetrieveSuccess
     */
    public function testAddSlotsBadRequestFailure($pitch): void
    {
        $pitchId = $pitch['data']['id'];
        
        $this->expectException(BadRequestHttpException::class);

        $this->client->request('GET', "/api/v1/pitches/$pitchId/slots");

        $this->client->request(
            'POST',
            "/api/v1/pitches/$pitchId/slots",
            [],
            [], 
            ['Content_Type' => 'application/json'], 
            '[]'
        );

        $actual = $this->client->getResponse();
    }
}
