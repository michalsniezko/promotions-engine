<?php

namespace App\Controller;

use App\DTO\LowestPriceEnquiry;
use App\Service\LowestPriceFinder;
use App\Service\Serializer\DTOSerializer;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductsController extends AbstractController
{

    public function __construct(
        private readonly DTOSerializer     $serializer,
        private readonly LowestPriceFinder $lowestPriceFinder,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Route('/products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
    public function lowestPrice(Request $request, int $id): Response
    {
        $lowestPriceEnquiry = $this->serializer->deserialize(
            $request->getContent(),
            LowestPriceEnquiry::class,
            'json'
        );

        $updatedEnquiry = $this->lowestPriceFinder->applyLowestPricePromotion($id, $lowestPriceEnquiry);
        $responseContent = $this->serializer->serialize($updatedEnquiry, 'json');

        return new JsonResponse(data: $responseContent, status: Response::HTTP_OK, json: true);
    }
}
