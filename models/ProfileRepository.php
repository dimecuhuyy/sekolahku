<?php
namespace app\models;

class ProfileRepository
{
    private const FILE = 'profile.json';

    public static function get(): array
    {
        return ContentStorage::readJson(self::FILE, [
            'visiMisi' => 'Mewujudkan peserta didik yang berakhlak mulia, cerdas, dan berprestasi.',
            'sejarah' => 'Sekolah berdiri sejak tahun 1990 dengan komitmen pada pendidikan berkualitas.',
            'nomorTelpon' => '0812-3456-7890',
            'akreditasi' => 'A',
            'profilSingkat' => 'Sekolah kami berfokus pada pengembangan karakter dan akademik.',
        ]);
    }

    public static function updateField(string $field, string $value): bool
    {
        $data = self::get();
        $data[$field] = $value;
        return ContentStorage::writeJson(self::FILE, $data);
    }
}

