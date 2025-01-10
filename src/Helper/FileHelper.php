<?php

namespace inIT\DecoratorExample\Helper;

use inIT\DecoratorExample\Exception\FileException;

class FileHelper
{
    private const LOG_FILE = _PS_MODULE_DIR_ . 'decoratorexample/maintenance.log';

    public function saveToFile(array $data): void
    {
        if (empty($data)) {
            // Brak zmian - nie rejestrujemy
            return;
        }

        $data = implode(PHP_EOL, $data);

        if (!file_put_contents(self::LOG_FILE, $data, FILE_APPEND | LOCK_EX)) {
            throw new FileException('Could not write to file');
        }
    }
}