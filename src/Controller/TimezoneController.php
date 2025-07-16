<?php
declare(strict_types=1);

namespace App\Controller;

use App\Attribute\Timezone;
use DateTimeImmutable;
use DateTimeZone;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/timezone')]
class TimezoneController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/current-time', name: 'current-time', methods: 'GET')]
    public function currentTime(#[Timezone(default: 'Europe/Warsaw')] string $timezone): JsonResponse
    {
        $now = new DateTimeImmutable('now', new DateTimeZone($timezone));
        $time = $now->format('Y-m-d H:i:s');

        return new JsonResponse(['timezone' => $timezone, 'time' => $time]);
    }
}
