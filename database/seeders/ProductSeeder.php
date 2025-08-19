<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $existingProduct = Product::where('code', 'TV01')->first();

        if (!$existingProduct) {
            Product::create([
                'name' => 'LG TV 50"',
                'code' => 'TV01',
                'model' => 'LG8768787',
                'price' => 18000.00,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'image' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1'
            ]);
        }
    }
}


## 3. مشكلة تكرار البيانات في ProductSeeder ✅

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $existingProduct = Product::where('code', 'TV01')->first();

        if (!$existingProduct) {
            Product::create([
                'name' => 'LG TV 50"',
                'code' => 'TV01',
                'model' => 'LG8768787',
                'price' => 18000.00,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'image' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1'
            ]);
        }
    }
}
```
