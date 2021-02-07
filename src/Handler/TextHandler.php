<?php

declare(strict_types=1);

namespace App\Handler;

/**
 * Class TextHandler
 * @package App\Handler
 */
class TextHandler
{
    private const ASTERISK = '*';
    private const UNDERSCORE = '_';

    private const MARKDOWN_DELIMITERS = [
        'bold_italic' => ['***', '___'],
        'bold' => ['**', '__'],
        'italic' => ['*', '_'],
    ];

    private const HTML_TAGS = [
        'bold_italic' => ['<b><i>', '</i></b>'],
        'bold' => ['<b>', '</b>'],
        'italic' => ['<i>', '</i>'],
    ];


    /**
     * @param string $text
     * @return mixed
     */
    public function getHtmlText(string $text)
    {
        //Если нет вхождений нужных знаков, то в тексте нечего обрабатывать
        if (strpos($text, self::ASTERISK) === false && strpos($text, self::UNDERSCORE) === false) {
            return $text;
        }

        return $this->markdownToHtml($text);
    }

    /**
     * @param string $text
     * @return string
     */
    private function markdownToHtml(string $text): string
    {
        foreach (self::MARKDOWN_DELIMITERS as $style => $delimiters) {
            foreach ($delimiters as $delimiter) {
                $regExp = $this->getRegExp($delimiter);
                if (preg_match_all($regExp, $text, $matches) > 0) {
                    foreach ($matches[0] as $foundText) {
                        $htmlTag = self::HTML_TAGS[$style];
                        $htmlText = $htmlTag[0] . str_replace($delimiter, '', $foundText) . $htmlTag[1];

                        $text = str_replace($foundText, $htmlText, $text);
                    }
                }
            }
        }

        return $text;
    }

    /**
     * @param string $delimiter
     * @return string
     */
    private function getRegExp(string $delimiter): string
    {
        $firstSymbol = $delimiter[0];
        $strLength = strlen($delimiter);

        return "/(\\$firstSymbol{" . $strLength . "}[^$firstSymbol]+\\$firstSymbol{" . $strLength . '})/';
    }
}