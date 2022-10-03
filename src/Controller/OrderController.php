<?php

namespace App\Controller;

use App\Entity\Order;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends ApiController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/api/orders", name="orders", methods={"GET"})
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $orders = $this->em->getRepository(Order::class)->findAll();
        $res = [];
        foreach ($orders as $order) {
            $res[] = [
                'orderCode' => $order->getId(),
                'productid' => $order->getProductId(),
                'quantity' => $order->getQuantity(),
                'address' => $order->getAddress(),
                'shippingDate' => $order->getShippingDate()
            ];
        }
        return $this->json($res);
    }

    /**
     * @Route("/api/order/{orderCode}", name="order", methods={"GET"})
     * @return JsonResponse
     */
    public function getOrder($orderCode): JsonResponse
    {
        $order = $this->em->getRepository(Order::class)->find($orderCode);
        if (!$order)
            return $this->respondValidationError("Invalid orderCode!");

        $res = [
            'orderCode' => $order->getId(),
            'productid' => $order->getProductId(),
            'quantity' => $order->getQuantity(),
            'address' => $order->getAddress(),
            'shippingDate' => $order->getShippingDate()
        ];
        return $this->json($res);
    }

    /**
     * @Route("/api/order", name="order-create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createOrder(Request $request): JsonResponse
    {
        $request = $this->transformJsonBody($request);
        $productId = $request->get('productId');
        $quantity = $request->get('quantity');
        $address = $request->get('address');

        if (empty($productId) || empty($quantity) || empty($address)) {
            return $this->respondValidationError("Invalid productId or quantity or address");
        }

        $order = new Order();
        $order->setProductId($productId);
        $order->setQuantity($quantity);
        $order->setAddress($address);
        $order->setCreatedAt(new DateTimeImmutable());
        $this->em->persist($order);
        $this->em->flush();
        return $this->respondWithSuccess("#{$order->getId()} order successfully created!");
    }

    /**
     * @Route("/api/order", name="order-update", methods={"PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function updateOrder(Request $request): JsonResponse
    {
        $request = $this->transformJsonBody($request);
        $orderCode = $request->get('orderCode');
        $productId = $request->get('productId');
        $quantity = $request->get('quantity');
        $address = $request->get('address');

        if (empty($orderCode) || empty($productId) || empty($quantity) || empty($address)) {
            return $this->respondValidationError("Invalid orderCode or productId or quantity or address");
        }

        $order = $this->em->getRepository(Order::class)->find($orderCode);
        if (!$order)
            return $this->respondValidationError("Invalid orderCode!");

        $shippingDate = $order->getShippingDate();
        if (!empty($shippingDate))
            return $this->respondValidationError("Your order has been shipped. Cannot be updated!");

        $order->setProductId($productId);
        $order->setQuantity($quantity);
        $order->setAddress($address);

        $this->em->persist($order);
        $this->em->flush();
        return $this->respondWithSuccess("#{$order->getId()} order successfully updated!");
    }

    /**
     * @Route("/api/order/shipping", name="order-shipping-update", methods={"PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function updateOrderShippengDate(Request $request): JsonResponse
    {
        $request = $this->transformJsonBody($request);
        $orderCode = $request->get('orderCode');
        $shippingDate = $request->get('shippingDate');

        if (empty($orderCode) || empty($shippingDate)) {
            return $this->respondValidationError("Invalid orderCode or shippingDate");
        }

        $order = $this->em->getRepository(Order::class)->find($orderCode);
        if (!$order)
            return $this->respondValidationError("Invalid orderCode!");

        $shippingDate = $order->getShippingDate();
        if (!empty($shippingDate))
            return $this->respondValidationError("Your order has been shipped. Cannot be updated!");

        $order->setShippingDate(new DateTimeImmutable($shippingDate));

        $this->em->persist($order);
        $this->em->flush();
        return $this->respondWithSuccess("#{$order->getId()} order successfully updated!");
    }
}
