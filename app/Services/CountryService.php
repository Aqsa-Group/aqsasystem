<?php
namespace App\Services;

class CountryService
{
    protected $countryCodes = [];

    public function __construct()
    {
        $jsonFile = __DIR__ . '/countrycode.json';

        if (file_exists($jsonFile)) {
            $countryData = json_decode(file_get_contents($jsonFile), true);

            $translations = [
                'Afghanistan' => 'افغانستان',
                'Iran' => 'ایران',
                'United States' => 'آمریکا/کانادا',
                'United Arab Emirates' => 'امارات',
                'Turkey' => 'ترکیه',
                'Japan' => 'ژاپن',
                'Germany' => 'آلمان',
                'France' => 'فرانسه',
                'India' => 'هند',
                'China' => 'چین',
            ];

            foreach ($countryData as $country) {
                $code = ltrim($country['dial_code'], '+');
                $name = $translations[$country['name']] ?? $country['name'];
                $this->countryCodes[$code] = "{$name} ({$country['dial_code']})";
            }
        }
    }

    public function getCountryCodes(): array
    {
        return $this->countryCodes;
    }
}