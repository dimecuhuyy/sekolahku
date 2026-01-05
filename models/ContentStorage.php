<?php
namespace app\models;

use Yii;

class ContentStorage
{
    public static function path(string $filename): string
    {
        return Yii::getAlias('@runtime/' . $filename);
    }

    public static function readJson(string $filename, $default)
    {
        $path = self::path($filename);
        if (!file_exists($path)) {
            self::writeJson($filename, $default);
            return $default;
        }
        $json = file_get_contents($path);
        $data = json_decode($json, true);
        return $data === null ? $default : $data;
    }

    public static function writeJson(string $filename, $data): bool
    {
        $path = self::path($filename);
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        return file_put_contents($path, $json) !== false;
    }
}

