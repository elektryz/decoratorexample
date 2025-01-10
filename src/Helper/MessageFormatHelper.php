<?php

namespace inIT\DecoratorExample\Helper;

class MessageFormatHelper
{
    private array $messages = [];

    public function getFormattedMessages(array $data): array
    {
        if (empty($data)) {
            return [];
        }

        $this->messages[] = 'Data zmiany: ' . date('Y-m-d H:i:s');
        $this->messages[] = '';

        foreach ($data as $key => $value) {
            if (!isset($value['old']) || !isset($value['new'])) {
                continue;
            }

            if (is_bool($value['old']) || is_bool($value['new'])) {
                $text = $value['new'] ? 'Włączono' : 'Wyłączono';
                $this->messages[$key] = $text . ' opcję "' . $key . '"';
            } else {
                $this->messages[$key] = 'Zmieniono wartość "' . $key . '" z "' . $value['old'] .
                    '" na "' . $value['new'] . '"';
            }
        }

        $this->messages[] = '';
        $this->messages[] = '';

        return $this->messages;
    }
}