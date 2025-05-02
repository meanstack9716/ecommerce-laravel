<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Men',
                'description' => 'Fashion and essentials for men',
                'img_path' => 'images/categories/mens-wear.jpg',
                'sub_categories' => [
                    [
                        'name' => 'Topwear',
                        'description' => 'Shirts, T-shirts, and more',
                        'img_path' => 'images/sub-categories/men-topwear.jpg',
                        'sub_sub_category' => [
                            ['name' => 'T-shirts', 'description' => 'Casual and printed T-shirts', 'img_path' => 'images/product-types/mens/tshirt.jpeg'],
                            ['name' => 'Casual Shirts', 'description' => 'Casual Shirts', 'img_path' => 'images/product-types/mens/casual-shirt.jpg'],
                            ['name' => 'Formal Shirts', 'description' => 'Formal Shirts', 'img_path' => 'images/product-types/mens/formal-shirt.jpg'],
                            ['name' => 'Sweatshirts', 'description' => 'Stylish Sweatshirts', 'img_path' => 'images/product-types/mens/sweatshirt.jpeg'],
                            ['name' => 'Jackets', 'description' => 'Bomber, denim & leather jackets', 'img_path' => 'images/product-types/mens/jacket.jpg'],
                            ['name' => 'Blazers & Coats', 'description' => 'Blazers & Coats', 'img_path' => 'images/product-types/mens/blazers.jpeg'],
                            ['name' => 'Suits', 'description' => 'Formal Suits', 'img_path' => 'images/product-types/mens/suits.jpg'],
                        ]
                    ],
                    [
                        'name' => 'Bottomwear',
                        'description' => 'Jeans, trousers, and more',
                        'img_path' => 'images/sub-categories/men-bottomwear.jpeg',
                        'sub_sub_category' => [
                            ['name' => 'Jeans', 'description' => 'Slim, regular & skinny fit', 'img_path' => 'images/product-types/mens/jeans.jpg'],
                            ['name' => 'Casual Trousers', 'description' => 'Casual Pants', 'img_path' => 'images/product-types/mens/casual-trousers.jpg'],
                            ['name' => 'Formal Trousers', 'description' => 'Formal Pants', 'img_path' => 'images/product-types/mens/formal-trousers.jpg'],
                            ['name' => 'Shorts', 'description' => 'Casual & sports shorts', 'img_path' => 'images/product-types/mens/shorts.jpg'],
                            ['name' => 'Track Pants & Joggers', 'description' => 'Comfortable joggers and Track Pants', 'img_path' => 'images/product-types/mens/tracks.jpg']
                        ]
                    ],
                    [
                        'name' => 'Footwear',
                        'description' => 'Shoes, sandals & more',
                        'img_path' => 'images/sub-categories/men-footwear.jpeg',
                        'sub_sub_category' => [
                            ['name' => 'Casual Shoes', 'description' => 'Casual Footwear', 'img_path' => 'images/product-types/mens/casual-shoes.jpg'],
                            ['name' => 'Sports Shoes', 'description' => 'Sports Footwear', 'img_path' => 'images/product-types/mens/sports-shoe.jpeg'],
                            ['name' => 'Formal Shoes', 'description' => 'Formal Shoes', 'img_path' => 'images/product-types/mens/formal-shoes.jpg'],
                            ['name' => 'Sneakers', 'description' => 'Trendy Sneakers', 'img_path' => 'images/product-types/mens/sneakers.jpg'],
                            ['name' => 'Sandals & Floaters', 'description' => 'Comfortable Sandals', 'img_path' => 'images/product-types/mens/sandals.jpg'],
                            ['name' => 'Flip Flops', 'description' => 'Relaxed Flip Flops', 'img_path' => 'images/product-types/mens/flipflops.jpg'],
                            ['name' => 'Socks', 'description' => 'Various Socks', 'img_path' => 'images/product-types/mens/socks.jpeg'],
                        ]
                    ],
                    [
                        'name' => 'Sports & Active Wear',
                        'description' => 'Sportswear for men',
                        'img_path' => 'images/sub-categories/men-sport.jpeg',
                        'sub_sub_category' => [
                            ['name' => 'Sports Shoes', 'description' => 'Running & Gym Shoes', 'img_path' => 'images/product-types/mens/running-shoes.jpg'],
                            ['name' => 'Track Pants & Shorts', 'description' => 'Active Bottomwear', 'img_path' => 'images/product-types/mens/sports-tracks.jpeg'],
                            ['name' => 'Sport T-Shirts', 'description' => 'Workout Tees', 'img_path' => 'images/product-types/mens/sports-tshirt.jpg'],
                            ['name' => 'TrackSuits', 'description' => 'Tracksuit set for workout', 'img_path' => 'images/product-types/mens/tracksuit.jpeg'],
                        ]
                    ],
                    [
                        'name' => 'Fashion Accessories',
                        'description' => 'Watches, bags & more',
                        'img_path' => 'images/sub-categories/men-accessories.jpg',
                        'sub_sub_category' => [
                            ['name' => 'Watches', 'description' => 'Analog & digital', 'img_path' => 'images/product-types/mens/watches.jpg'],
                            ['name' => 'Belts', 'description' => 'Leather & casual belts', 'img_path' => 'images/product-types/mens/belts.jpg'],
                            ['name' => 'Wallets', 'description' => 'Leather & RFID wallets', 'img_path' => 'images/product-types/mens/wallets.jpeg'],
                            ['name' => 'Sunglasses', 'description' => 'Aviators & wayfarers', 'img_path' => 'images/product-types/mens/sunglasses.jpg'],
                            ['name' => 'Perfumes & Body Mists', 'description' => 'Fragrances', 'img_path' => 'images/product-types/mens/perfumes.png'],
                            ['name' => 'Ties, Cufflinks & Pocket Squares', 'description' => 'Formal Accessories', 'img_path' => 'images/product-types/mens/tie.jpeg'],
                            ['name' => 'Jewelery', 'description' => 'Jeweleries like chains ring and all', 'img_path' => 'images/product-types/mens/chain.jpg'],
                        ]
                    ],
                    [
                        'name' => 'Ethnic Wear',
                        'description' => 'Traditional clothing',
                        'img_path' => 'images/sub-categories/men-ethnic.jpeg',
                        'sub_sub_category' => [
                            ['name' => 'Kurta Pajama', 'description' => 'Cotton & silk kurtas and pajamas', 'img_path' => 'images/product-types/mens/kurta-pajama.jpg'],
                            ['name' => 'Sherwanis', 'description' => 'Wedding & festive wear', 'img_path' => 'images/product-types/mens/sherwani.jpeg'],
                            ['name' => 'Indo western', 'description' => 'Modern style indian ethic wear', 'img_path' => 'images/product-types/mens/indo-western.jpg']
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Women',
                'description' => 'Fashion and essentials for women',
                'img_path' => 'images/categories/women-wear.jpg',
                'sub_categories' => [
                    [
                        'name' => 'Western Wear',
                        'description' => 'Trendy and cool designs with great popularity',
                        'img_path' => 'images/sub-categories/women-westernwear.jpg',
                        'sub_sub_category' => [
                            ['name' => 'Dresses', 'description' => '', 'img_path' => 'images/product-types/women/dress.jpg'],
                            ['name' => 'Tops', 'description' => '', 'img_path' => 'images/product-types/women/top.jpg'],
                            ['name' => 'Tshirts', 'description' => '', 'img_path' => 'images/product-types/women/tshirt.jpg'],
                            ['name' => 'Shirts', 'description' => 'Formal & casual shirts', 'img_path' => 'images/product-types/women/shirt.jpeg'],
                            ['name' => 'Jeans', 'description' => '', 'img_path' => 'images/product-types/women/jeans.jpeg'],
                            ['name' => 'Shorts & Skirts', 'description' => '', 'img_path' => 'images/product-types/women/skirt.jpg'],
                            ['name' => 'Co-ords', 'description' => '', 'img_path' => 'images/product-types/women/coord.jpeg'],
                            ['name' => 'Jumpsuits', 'description' => '', 'img_path' => 'images/product-types/women/jumpsuit.jpeg'],
                            ['name' => 'Sweaters & Sweatshirts', 'description' => '', 'img_path' => 'images/product-types/women/sweatshirt.jpeg'],
                            ['name' => 'Jackets & Coats', 'description' => '', 'img_path' => 'images/product-types/women/jacket.jpg'],
                            ['name' => 'Blazers & Waistcoats', 'description' => '', 'img_path' => 'images/product-types/women/women-blazer.jpg'],
                        ]
                    ],
                    [
                        'name' => 'Indian & Fusion Wear',
                        'description' => 'Indian and Traditional wear for women',
                        'img_path' => 'images/sub-categories/women-indian.jpg',
                        'sub_sub_category' => [
                            ['name' => 'Kurtas & Suits', 'description' => '', 'img_path' => 'images/product-types/women/kurta.jpg'],
                            ['name' => 'Kurtis, Tunics & Tops', 'description' => '', 'img_path' => 'images/product-types/women/kurti.jpg'],
                            ['name' => 'Sarees', 'description' => '', 'img_path' => 'images/product-types/women/saree.jpg'],
                            ['name' => 'Ethnic Wear', 'description' => '', 'img_path' => 'images/product-types/women/ethnic-wear.jpeg'],
                            ['name' => 'Leggings, Salwars & Churidars', 'description' => '', 'img_path' => 'images/product-types/women/legging.jpeg'],
                            ['name' => 'Skirts & Palazzos', 'description' => '', 'img_path' => 'images/product-types/women/palazzo.jpg'],
                            ['name' => 'Lehenga Cholis', 'description' => '', 'img_path' => 'images/product-types/women/lehenga.jpeg'],
                            ['name' => 'Dupattas & Shawls', 'description' => '', 'img_path' => 'images/product-types/women/shawl.jpeg'],
                        ]
                    ],
                    [
                        'name' => 'Footwear',
                        'description' => 'Heels, flats & more',
                        'img_path' => 'images/sub-categories/women-footwear.jpg',
                        'sub_sub_category' => [
                            ['name' => 'Heels', 'description' => 'Stilettos & block heels', 'img_path' => 'images/product-types/women/heels.jpg'],
                            ['name' => 'Flats', 'description' => 'Ballet & loafers', 'img_path' => 'images/product-types/women/flatwear.jpg'],
                            ['name' => 'Casual Shoes', 'description' => 'Everyday Wear', 'img_path' => 'images/product-types/women/casual-shoes.jpeg'],
                            ['name' => 'Boots', 'description' => 'Ankle and Knee Boots', 'img_path' => 'images/product-types/women/boots.jpg'],
                            ['name' => 'Flip Flops', 'description' => 'Casual Footwear', 'img_path' => 'images/product-types/women/flipflop.jpg'],
                            ['name' => 'Sandals', 'description' => 'Casual & strappy', 'img_path' => 'images/product-types/women/sandal.jpg'],
                            ['name' => 'Sports Shoes', 'description' => 'Running & gym shoes', 'img_path' => 'images/product-types/women/sport-shoes.jpeg']
                        ]
                    ],
                    [
                        'name' => 'Sports & Active Wear',
                        'description' => 'Sportswear for women',
                        'img_path' => 'images/sub-categories/women-sportwear.jpeg',
                        'sub_sub_category' => [
                            ['name' => 'Sports Shoes', 'description' => 'Running & Gym Shoes', 'img_path' => 'images/product-types/women/running-shoes.jpeg'],
                            ['name' => 'Track Pants & Shorts', 'description' => 'Active Bottomwear', 'img_path' => 'images/product-types/women/track-pant.jpg'],
                            ['name' => 'Sport T-Shirts', 'description' => 'Workout Tees', 'img_path' => 'images/product-types/women/gym-tee.jpeg'],
                            ['name' => 'TrackSuits', 'description' => 'Tracksuit set for workout', 'img_path' => 'images/product-types/women/tracksuit.jpg'],
                        ]
                    ],
                    [
                        'name' => 'Fashion Accessories',
                        'description' => 'Jewelry, bags & more',
                        'img_path' => 'images/sub-categories/women-accesories.jpg',
                        'sub_sub_category' => [
                            ['name' => 'Handbags', 'description' => 'Tote, sling & clutch', 'img_path' => 'images/product-types/women/handbag.jpg'],
                            ['name' => 'Jewelry', 'description' => 'Earrings, necklaces & rings', 'img_path' => 'images/product-types/women/jewelery.jpeg'],
                            ['name' => 'Watches', 'description' => 'Elegant Timepieces', 'img_path' => 'images/product-types/women/watch.jpg'],
                            ['name' => 'Sunglasses', 'description' => 'Trendy shades', 'img_path' => 'images/product-types/women/sunglasses.jpg'],
                            ['name' => 'Belts', 'description' => 'Functional & Decorative Belts', 'img_path' => 'images/product-types/women/belt.jpg'],
                        ]
                    ],
                    [
                        'name' => 'Beauty & Personal Care',
                        'description' => 'Makeup and skincare products',
                        'img_path' => 'images/sub-categories/women-makeup.jpeg',
                        'sub_sub_category' => [
                            ['name' => 'Makeup', 'description' => '', 'img_path' => 'images/product-types/women/makeup.jpeg'],
                            ['name' => 'Skincare', 'description' => '', 'img_path' => 'images/product-types/women/skincare.jpg'],
                            ['name' => 'Fragrances', 'description' => '', 'img_path' => 'images/product-types/women/fragrances.jpg'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Children',
                'description' => 'Kids clothing & accessories',
                'img_path' => 'images/categories/child-wear.jpeg',
                'sub_categories' => [
                    [
                        'name' => 'Boys Fashion',
                        'description' => 'Clothing for boys',
                        'img_path' => 'images/sub-categories/boys-wear.jpeg',
                        'sub_sub_category' => [
                            ['name' => 'T-shirts', 'description' => 'Cartoon & printed', 'img_path' => 'images/product-types/children/boys-tshirt.jpeg'],
                            ['name' => 'Shirts', 'description' => 'Casual & formal', 'img_path' => 'images/product-types/children/boys-shirt.jpg'],
                            ['name' => 'Shorts', 'description' => 'Summer wear', 'img_path' => 'images/product-types/children/boys-short.jpeg']
                        ]
                    ],
                    [
                        'name' => 'Girls Fashion',
                        'description' => 'Clothing for girls',
                        'img_path' => 'images/sub-categories/girls-wear.jpeg',
                        'sub_sub_category' => [
                            ['name' => 'Dresses', 'description' => 'Frocks & party wear', 'img_path' => 'images/product-types/children/girls-dress.jpeg'],
                            ['name' => 'Tops', 'description' => 'Casual & stylish', 'img_path' => 'images/product-types/children/girls-tops.jpg'],
                            ['name' => 'Skirts', 'description' => 'Tutu & flared skirts', 'img_path' => 'images/product-types/children/girls-skirts.jpeg']
                        ]
                    ],
                    [
                        'name' => 'Infants',
                        'description' => 'Baby clothing',
                        'img_path' => 'images/sub-categories/infant-wear.jpeg',
                        'sub_sub_category' => [
                            ['name' => 'Bodysuits', 'description' => 'Soft cotton wear', 'img_path' => 'images/product-types/children/infant-bodysuit.jpeg'],
                            ['name' => 'Rompers', 'description' => 'Comfortable onesies', 'img_path' => 'images/product-types/children/infant-romper.jpeg'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Home',
                'description' => 'Home decor & essentials',
                'img_path' => 'images/categories/home.jpeg',
                'sub_categories' => [
                    [
                        'name' => 'Bed Linen',
                        'description' => 'Bedding items',
                        'img_path' => 'images/sub-categories/home-bed.jpeg',
                        'sub_sub_category' => [
                            ['name' => 'Bedsheets', 'description' => 'Cotton & Silk Bedsheets', 'img_path' => 'images/product-types/home/bedsheet.jpg'],
                            ['name' => 'Blankets', 'description' => 'Warm Blankets', 'img_path' => 'images/product-types/home/blankets.jpg'],
                            ['name' => 'Pillow Covers', 'description' => 'Pillow Cases', 'img_path' => 'images/product-types/home/pillow-covers.jpeg'],
                        ]
                    ],
                    [
                        'name' => 'Curtains & Carpets',
                        'description' => 'Home decor fabrics',
                        'img_path' => 'images/sub-categories/home-curtains.jpeg',
                        'sub_sub_category' => [
                            ['name' => 'Curtains', 'description' => 'Stylish Curtains', 'img_path' => 'images/product-types/home/curtains.jpg'],
                            ['name' => 'Carpets', 'description' => 'Decorative Rugs', 'img_path' => 'images/product-types/home/carpets.jpeg'],
                        ]
                    ]
                ]
            ]
        ];

        foreach ($categories as $category) {
            $existingCategory = Category::where('name', $category['name'])->first();
            $categoryId = null;

            if (!$existingCategory) {
                $img_path = null;
                
                $sourcePath = public_path($category['img_path']);
                
                if (File::exists($sourcePath)) {
                    $storagePath = 'categories/' . basename($category['img_path']);
                    
                    if (!Storage::exists($storagePath)) {
                        Storage::put($storagePath, File::get($sourcePath));
                    }
                    
                    $img_path = $storagePath;
                }
                
                $newCatergory = Category::create([
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'img_path' => $img_path,
                ]);

                $categoryId = $newCatergory->id;
            } else {
                $categoryId = $existingCategory->id;
            }

            foreach($category['sub_categories'] as $subCat) {
                $existingSubCategory = SubCategory::where('name', $subCat['name'])
                    ->where('category_id', $categoryId)
                    ->first();
                $subCategoryId = null;

                if (!$existingSubCategory) {
                    $sub_img_path = null;
                    if ($subCat['img_path']) {
                    $subSourcePath = public_path($subCat['img_path']);
                    
                    if (File::exists($subSourcePath)) {
                        $storagePath = 'sub_categories/'.$categoryId. '/' . basename($subCat['img_path']);
                        
                        if (!Storage::exists($storagePath)) {
                            Storage::put($storagePath, File::get($subSourcePath));
                        }
                        
                        $sub_img_path = $storagePath;
                    }
                }
                    
                    $newSubCatergory = SubCategory::create([
                        'name' => $subCat['name'],
                        'description' => $subCat['description'],
                        'img_path' => $sub_img_path,
                        'category_id' => $categoryId
                    ]);
    
                    $subCategoryId = $newSubCatergory->id;
                } else {
                    $subCategoryId = $existingSubCategory->id;
                }

                foreach($subCat['sub_sub_category'] as $subSub) {
                    $existingType = SubSubCategory::where('name', $subSub['name'])
                        ->where('category_id', $categoryId)
                        ->where('sub_category_id', $subCategoryId)
                        ->first();
    
                    if (!$existingType) {
                        $type_img_path = null;
                        if ($subSub['img_path']) {
                        $typeSourcePath = public_path($subSub['img_path']);
                        
                        if (File::exists($typeSourcePath)) {
                            $storagePath = 'sub_sub_categories/'.$subCategoryId. '/' . basename($subSub['img_path']);
                            
                            if (!Storage::exists($storagePath)) {
                                Storage::put($storagePath, File::get($typeSourcePath));
                            }
                            
                            $type_img_path = $storagePath;
                        }
                    }
                        
                        $newSubCatergory = SubSubCategory::create([
                            'name' => $subSub['name'],
                            'description' => $subSub['description'],
                            'img_path' => $type_img_path,
                            'category_id' => $categoryId,
                            'sub_category_id' => $subCategoryId,
                        ]);
        
                    }
                }
            }
        }
        //
    }
}



