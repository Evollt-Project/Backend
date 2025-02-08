<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CertificateType>
 */
class CertificateTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => 'certificate_types/F4SHcnx858rKzakjAGGJ9pFb2A6aokfhbKTI2kOE.jpg',
            'user_id' => 1,
            'title' => 'Дефолтный сертификат',
            'title_position' => json_encode([
                'x' => '111',
                'y' => '111'
            ]),
            'date_position' => json_encode([
                'x' => '111',
                'y' => '111'
            ]),
            'logo_position' => json_encode([
                'x' => '111',
                'y' => '111'
            ]),
        ];
    }
}
