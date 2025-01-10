<?php

namespace inIT\DecoratorExample\Decorator;

use inIT\DecoratorExample\Helper\CompareHelper;
use inIT\DecoratorExample\Helper\FileHelper;
use inIT\DecoratorExample\Helper\MessageFormatHelper;
use PrestaShop\PrestaShop\Adapter\Shop\MaintenanceConfiguration;
use PrestaShop\PrestaShop\Core\Form\Handler;

class MaintenanceDecorator
{
    public function __construct(
        private readonly Handler $maintenanceHandler,
        private readonly MaintenanceConfiguration $maintenanceConfiguration,
        private readonly CompareHelper $compareHelper,
        private readonly MessageFormatHelper $messageFormatHelper,
        private readonly FileHelper $fileHelper,
    )
    {
    }

    public function getForm()
    {
        return $this->maintenanceHandler->getForm();
    }

    public function save(array $data)
    {
        $currentValues = $this->maintenanceConfiguration->getConfiguration();

        // Dane z konfiguracji jako pierwszy argument & dane z formularza jako drugi
        $diff = $this->compareHelper->getDiff($currentValues, $data);
        $formatMessage = $this->messageFormatHelper->getFormattedMessages($diff);
        $this->fileHelper->saveToFile($formatMessage);

        return $this->maintenanceHandler->save($data);
    }
}