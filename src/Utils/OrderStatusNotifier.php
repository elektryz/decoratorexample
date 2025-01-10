<?php

namespace inIT\DecoratorExample\Utils;

use inIT\DecoratorExample\Exception\OrderStateException;

class OrderStatusNotifier
{
    public function __construct(private readonly \Context $context)
    {}

    public function getMessage(int $orderId, int $newOrderStatusId): string
    {
        $order = new \Order($orderId);
        $orderStateCurrent = new \OrderState($order->current_state);

        if (!\Validate::isLoadedObject($orderStateCurrent)) {
            throw new OrderStateException('Order state not found');
        }

        $orderStateNew = new \OrderState($newOrderStatusId);

        $idLang = $this->context->language->id;

        return 'Zmieniono status zamówienia nr '.$order->reference.' (ID '.$orderId.') 
        z "'.$orderStateCurrent->name[$idLang].'" na "'.$orderStateNew->name[$idLang].'"';
    }
}