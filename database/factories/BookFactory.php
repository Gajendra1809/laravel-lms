<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    protected $model = \App\Models\Book::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(2),
            'author' => $this->faker->name(),
            'isbn' => $this->faker->isbn13(),
            'published_date' => $this->faker->date(),
            'admin_id' => 3,
        ];
    }
}
