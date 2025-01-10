<?php

namespace inIT\DecoratorExample\Helper;

use inIT\DecoratorExample\Exception\ArrayStructureException;
use inIT\DecoratorExample\Exception\LanguageException;

class CompareHelper
{
    private array $diff = [];
    private array $languages = [];

    public function __construct()
    {
        if ($this->languages == []) {
            $langs = \Language::getLanguages(false);

            if (empty($langs)) {
                throw new LanguageException('No languages found.');
            }

            foreach ($langs as $lang) {
                $this->languages[(int)$lang['id_lang']] = $lang['name'];
            }
        }
    }

    public function getDiff(array $a1, array $a2, $recursiveKey = ''): array
    {
        if (empty($a1) && empty($a2)) {
            return [];
        }

        if (empty($a1)) {
            return $a2;
        }

        if (empty($a2)) {
            return $a1;
        }

        foreach ($a1 as $key => $value) {
            if (!isset($a2[$key])) {
                throw new ArrayStructureException(
                    'Array structure is not the same between two arrays. One array is missing a key "' . $key . '".'
                );
            }

            if (!is_array($value)) {
                if ($value !== $a2[$key]) {
                    $keyDiff = $key;
                    if ($recursiveKey) {
                        $idLang = (int)$key;
                        $keyDiff = $recursiveKey .
                        isset($this->languages[$key]) ? ' ' . '(' . $this->languages[$key] . ')' : '_' . $idLang;
                    }
                    $this->diff[$keyDiff] = [
                        'old' => is_bool($value) ? $value : strip_tags($value),
                        'new' => is_bool($a2[$key]) ? $value : strip_tags($a2[$key]),
                    ];
                }
            } else {
                $this->getDiff($value, $a2[$key], $key);
            }
        }

        return $this->diff;
    }
}