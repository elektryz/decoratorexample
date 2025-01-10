<?php

namespace inIT\DecoratorExample\Decorator;

use inIT\DecoratorExample\Utils\OrderStatusNotifier;
use PrestaShop\PrestaShop\Core\Search\Filters\OrderFilters;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Controller\Admin\Sell\Order\OrderController;
use PrestaShopBundle\Form\Admin\Sell\Order\UpdateOrderStatusType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderControllerDecorator extends FrameworkBundleAdminController
{
    public function __construct(
        private readonly OrderController $decoratedController,
        private readonly OrderStatusNotifier $orderStatusNotifier
    )
    {
    }

    public function updateStatusFromListAction(int $orderId, Request $request): RedirectResponse
    {
        $newIdOrder = $request->request->getInt('value');
        $notifierMessage = $this->orderStatusNotifier->getMessage($orderId, $newIdOrder);
        $this->addFlash('success', $notifierMessage);

        return $this->decoratedController->updateStatusFromListAction($orderId, $request);
    }

    public function updateStatusAction(int $orderId, Request $request): RedirectResponse
    {
        $formFactory = $this->get('form.factory');

        $form = $formFactory->createNamed(
            'update_order_status',
            UpdateOrderStatusType::class
        );
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $form = $formFactory->createNamed(
                'update_order_status_action_bar',
                UpdateOrderStatusType::class
            );
            $form->handleRequest($request);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $newIdOrder = $form->getData()['new_order_status_id'];
            $notifierMessage = $this->orderStatusNotifier->getMessage($orderId, $newIdOrder);
            $this->addFlash('success', $notifierMessage);
        }

        return $this->decoratedController->updateStatusAction($orderId, $request);
    }

    public function indexAction(Request $request, OrderFilters $filters)
    {
        return $this->decoratedController->indexAction($request, $filters);
    }

    public function viewAction(int $orderId, Request $request): Response
    {
        return $this->decoratedController->viewAction($orderId, $request);
    }
}