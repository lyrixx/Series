<?php

namespace Series\Utils;

class GuessInfo
{

    public function extractVersion($string, $force = true)
    {
        // s03E05
        if (preg_match('/s(\d+)e(\d+)/i', $string, $match)) {
            return sprintf('%d.%d', $match[1], $match[2]);
        }

        // 03x05
        if (preg_match('/(\d+)x(\d+)/i', $string, $match)) {
            return sprintf('%d.%d', $match[1], $match[2]);
        }

        if (!$force) {
            throw new \RuntimeException(sprintf('Could not extract version in "%s"', $string));
        }

        return '0.0';
    }

    public function isSameShow($show1, $show2)
    {
        $show1 = str_replace('-', '', $this->slugify($show1));
        $show2 = str_replace('-', '', $this->slugify($show2));

        if ($show1 == $show2) {
            return true;
        }
        if (false !== strpos($show1, $show2) || false !== strpos($show2, $show1)) {
            return true;
        }

        return false;
    }

    private function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return '';
        }

        return $text;
    }
}
