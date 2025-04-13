<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and accessories',
                'children' => [
                    ['name' => 'Smartphones', 'description' => 'Mobile phones and accessories'],
                    ['name' => 'Laptops', 'description' => 'Notebooks and accessories'],
                    ['name' => 'Audio', 'description' => 'Headphones, speakers and audio equipment'],
                ]
            ],
            [
                'name' => 'Clothing',
                'description' => 'Apparel and fashion items',
                'children' => [
                    ['name' => 'Men', 'description' => 'Men\'s clothing and accessories'],
                    ['name' => 'Women', 'description' => 'Women\'s clothing and accessories'],
                    ['name' => 'Kids', 'description' => 'Children\'s clothing and accessories'],
                ]
            ],
            [
                'name' => 'Home & Kitchen',
                'description' => 'Products for your home',
                'children' => [
                    ['name' => 'Furniture', 'description' => 'Tables, chairs, and other furniture'],
                    ['name' => 'Appliances', 'description' => 'Kitchen and home appliances'],
                    ['name' => 'Decor', 'description' => 'Decorative items for your home'],
                ]
            ],
            [
                'name' => 'Books',
                'description' => 'Books and publications',
                'children' => [
                    ['name' => 'Fiction', 'description' => 'Novels and fiction books'],
                    ['name' => 'Non-Fiction', 'description' => 'Educational and informative books'],
                    ['name' => 'Children\'s Books', 'description' => 'Books for children and young adults'],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);
            
            $category = Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'description' => $categoryData['description'],
            ]);
            
            foreach ($children as $childData) {
                Category::create([
                    'name' => $childData['name'],
                    'slug' => Str::slug($childData['name']),
                    'description' => $childData['description'],
                    'parent_id' => $category->id,
                ]);
            }
        }

        // Create products
        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'The latest iPhone with advanced features and powerful performance.',
                'price' => 999.99,
                'compare_price' => 1099.99,
                'status' => 'published',
                'sku' => 'IP15PRO-128',
                'quantity' => 50,
                'categories' => ['Smartphones']
            ],
            [
                'name' => 'MacBook Air M2',
                'description' => 'Lightweight and powerful laptop with the M2 chip for exceptional performance.',
                'price' => 1299.99,
                'compare_price' => 1399.99,
                'status' => 'published',
                'sku' => 'MBA-M2-256',
                'quantity' => 30,
                'categories' => ['Laptops']
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'description' => 'Premium noise-cancelling headphones with exceptional sound quality.',
                'price' => 349.99,
                'compare_price' => 399.99,
                'status' => 'published',
                'sku' => 'SNY-WH1000XM5',
                'quantity' => 100,
                'categories' => ['Audio']
            ],
            [
                'name' => 'Men\'s Classic T-shirt',
                'description' => 'Comfortable cotton t-shirt for everyday wear.',
                'price' => 24.99,
                'compare_price' => 29.99,
                'status' => 'published',
                'sku' => 'MTS-BLK-M',
                'quantity' => 200,
                'categories' => ['Men']
            ],
            [
                'name' => 'Women\'s Summer Dress',
                'description' => 'Light and stylish dress perfect for summer days.',
                'price' => 49.99,
                'compare_price' => 59.99,
                'status' => 'published',
                'sku' => 'WSD-WHT-M',
                'quantity' => 150,
                'categories' => ['Women']
            ],
            [
                'name' => 'Coffee Table',
                'description' => 'Modern coffee table with wooden top and metal legs.',
                'price' => 149.99,
                'compare_price' => 179.99,
                'status' => 'published',
                'sku' => 'FRN-CT-OAK',
                'quantity' => 25,
                'categories' => ['Furniture']
            ],
            [
                'name' => 'Blender',
                'description' => 'High-powered blender for smoothies and food preparation.',
                'price' => 79.99,
                'compare_price' => 99.99,
                'status' => 'published',
                'sku' => 'APL-BLN-1000',
                'quantity' => 40,
                'categories' => ['Appliances']
            ],
            [
                'name' => 'The Great Gatsby',
                'description' => 'Classic novel by F. Scott Fitzgerald.',
                'price' => 12.99,
                'compare_price' => 14.99,
                'status' => 'published',
                'sku' => 'BK-FIC-GATSBY',
                'quantity' => 100,
                'categories' => ['Fiction']
            ],
        ];

        foreach ($products as $productData) {
            $categoryNames = $productData['categories'];
            unset($productData['categories']);
            
            $product = Product::create($productData);
            
            // Attach categories
            $categories = Category::whereIn('name', $categoryNames)->get();
            $product->categories()->attach($categories->pluck('id')->toArray());
        }
    }
} 