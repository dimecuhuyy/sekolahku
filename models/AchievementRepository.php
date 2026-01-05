<?php
namespace app\models;

class AchievementRepository
{
    private const FILE = 'achievements.json';

    public static function all(): array
    {
        $items = ContentStorage::readJson(self::FILE, []);
        usort($items, function ($a, $b) {
            return ($b['createdAt'] ?? 0) <=> ($a['createdAt'] ?? 0);
        });
        return $items;
    }

    public static function create(array $item): array
    {
        $items = ContentStorage::readJson(self::FILE, []);
        $item['id'] = $item['id'] ?? uniqid('ach_', true);
        $item['createdAt'] = time();
        $items[] = $item;
        ContentStorage::writeJson(self::FILE, $items);
        return $item;
    }

    public static function update(string $id, array $patch): bool
    {
        $items = ContentStorage::readJson(self::FILE, []);
        foreach ($items as &$it) {
            if ($it['id'] === $id) {
                $it = array_merge($it, $patch);
                ContentStorage::writeJson(self::FILE, $items);
                return true;
            }
        }
        return false;
    }

    public static function delete(string $id): bool
    {
        $items = ContentStorage::readJson(self::FILE, []);
        $items = array_values(array_filter($items, function ($it) use ($id) {
            return $it['id'] !== $id;
        }));
        return ContentStorage::writeJson(self::FILE, $items);
    }
}

