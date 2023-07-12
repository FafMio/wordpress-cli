<?php

namespace App\Service;

class FileService
{

    public static function setup(string $wordpressThemePath): void
    {
        $content = file_get_contents(".env.local");
        if (str_contains($content, 'WORDPRESS_THEME_DIR')) {
            static::modifyEnv(["WORDPRESS_THEME_DIR" => $wordpressThemePath]);
        } else {
            static::addEnv("WORDPRESS_THEME_DIR", $wordpressThemePath);
        }
    }

    public static function changeBlockCategory(string $blockCategorySlug): void
    {
        $content = file_get_contents(".env.local");
        if (str_contains($content, 'BLOCK_CATEGORY_SLUG')) {
            static::modifyEnv(["BLOCK_CATEGORY_SLUG" => $blockCategorySlug]);
        } else {
            static::addEnv("BLOCK_CATEGORY_SLUG", $blockCategorySlug);
        }
    }

    public static function fileReplaceContent(string $path, string $oldContent, string $newContent): void
    {
        $str = file_get_contents($path);
        $str = str_replace($oldContent, $newContent, $str);
        file_put_contents($path, $str);
    }

    public static function modifyEnv($values = []): void
    {
        $path = ".env.local";
        $env = [];
        foreach (parse_ini_file($path) as $k => $value) {
            $env[$k] = "$k={$value}";

            if (array_key_exists($k, $values)) {
                $env[$k] = "$k={$values[$k]}";
            }
        }
        file_put_contents($path, implode(PHP_EOL, $env));
    }

    public static function addEnv(string $key, string $value): void
    {
        $myfile = fopen(".env.local", "a") or die("Unable to open file!");
        $txt = "$key=$value";
        fwrite($myfile, "\n" . $txt);
        fclose($myfile);
    }

    public static function appendFile(string $path, string $content): void
    {
        $myfile = fopen($path, "a") or die("Unable to open file!");
        fwrite($myfile, "\n" . $content);
        fclose($myfile);
    }
}