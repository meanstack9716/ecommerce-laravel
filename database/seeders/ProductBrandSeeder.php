<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductBrand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
              "name" => "Puma",
              "description" => "Puma is a German multinational company that designs and manufactures athletic and casual footwear, apparel and accessories.",
              "img_path" => "/images/brands/puma.png"
            ],
            [
              "name" => "Nike",
              "description" => "Nike is an American multinational corporation that is engaged in the design, development, manufacturing, and marketing of footwear, apparel, equipment, and accessories.",
              "img_path" => "/images/brands/nike.png"
            ],
            [
              "name" => "Adidas",
              "description" => "Adidas is a German multinational corporation that designs and manufactures shoes, clothing, and accessories, known for its performance and sportswear lines.",
              "img_path" => "/images/brands/adidas.png"
            ],
            [
              "name" => "Zara",
              "description" => "Zara is a Spanish apparel retailer known for its trendy and affordable fashion, offering clothing, accessories, shoes, swimwear, beauty, and perfumes.",
              "img_path" => "/images/brands/zara.png"
            ],
            [
              "name" => "H&M",
              "description" => "H&M is a Swedish multinational clothing company known for offering fashion-forward and affordable clothing and accessories for all age groups.",
              "img_path" => "/images/brands/h&m.png"
            ],
            [
              "name" => "Levi's",
              "description" => "Levi's is an American clothing company best known for its iconic denim jeans and casualwear.",
              "img_path" => "/images/brands/levis.png"
            ],
            [
              "name" => "Gucci",
              "description" => "Gucci is an Italian luxury fashion brand known for its high-end clothing, accessories, and leather goods.",
              "img_path" => "/images/brands/gucci.png"
            ],
            [
                "name" => "Allen Solly",
                "description" => "Allen Solly is a popular Indian brand known for its contemporary and stylish clothing for men, women, and kids, offering a mix of formal and casual wear.",
                "img_path" => "/images/brands/allen-solly.png"
            ],
            [
                "name" => "Arrow",
                "description" => "Arrow is a well-known brand offering premium formal and casual wear for men, including shirts, trousers, and accessories.",
                "img_path" => "/images/brands/arrow.png"
            ],
            [
                "name" => "Being Human",
                "description" => "Being Human is a brand by Salman Khan, known for its trendy and affordable clothing that supports charitable causes.",
                "img_path" => "/images/brands/being-human.png"
            ],
            [
                "name" => "U.S. Polo Assn",
                "description" => "U.S. Polo Assn is the official brand of the United States Polo Association, offering stylish and sporty apparel for men, women, and kids.",
                "img_path" => "/images/brands/us-polo.png"
            ],
            [
                "name" => "Tommy Hilfiger",
                "description" => "Tommy Hilfiger is a global premium lifestyle brand known for its classic American cool style, offering clothing, accessories, and footwear.",
                "img_path" => "/images/brands/tommy-hilfiger.png"
            ],
            [
                "name" => "Pepe Jeans",
                "description" => "Pepe Jeans is a global denim and casual wear brand known for its stylish and high-quality jeans and apparel.",
                "img_path" => "/images/brands/pepe-jeans.png"
            ],
            [
                "name" => "Blackberrys",
                "description" => "Blackberrys is an Indian brand offering premium formal and casual wear for men, known for its sophisticated designs.",
                "img_path" => "/images/brands/blackberry.png"
            ],
            [
                "name" => "Bewakoof",
                "description" => "Bewakoof is a youth-centric brand known for its quirky and humorous graphic t-shirts, casual wear, and accessories.",
                "img_path" => "/images/brands/bewakoof.png"
            ],
            [
                "name" => "Woodland",
                "description" => "Woodland is known for its rugged outdoor footwear and apparel, offering durable and eco-friendly products for adventure enthusiasts.",
                "img_path" => "/images/brands/woodland.png"
            ],
            [
                "name" => "Superdry",
                "description" => "Superdry is a British brand famous for its Japanese-inspired graphics, vintage Americana styles, and high-quality casual wear.",
                "img_path" => "/images/brands/superdry.png"
            ],
            [
                "name" => "Reebok",
                "description" => "Reebok is a global athletic footwear and apparel brand, specializing in fitness and sports-inspired casual wear.",
                "img_path" => "/images/brands/reebok.png"
            ],
            [
                "name" => "Monte Carlo",
                "description" => "Monte Carlo is an Indian brand offering premium woolens, winter wear, and casual clothing with a focus on comfort and style.",
                "img_path" => "/images/brands/monte-carlo.png"
            ],
            [
                "name" => "Cantabil",
                "description" => "Cantabil is a popular Indian brand known for its trendy and affordable formal and casual wear for men and women.",
                "img_path" => "/images/brands/cantabil.png"
            ],
            [
                "name" => "Wrangler",
                "description" => "Wrangler is a legendary denim brand offering durable jeans, shirts, and western wear for men and women.",
                "img_path" => "/images/brands/wrangler.png"
            ],
            [
                "name" => "Jack & Jones",
                "description" => "Jack & Jones is a Danish brand known for its stylish and contemporary menswear, including jeans, shirts, and jackets.",
                "img_path" => "/images/brands/jack-jones.png"
            ],
            [
                "name" => "Flying Machine",
                "description" => "Flying Machine is an Indian youth-centric brand known for its trendy denim and casual wear.",
                "img_path" => "/images/brands/flying-machine.png"
            ],
            [
                "name" => "FabIndia",
                "description" => "FabIndia is known for its ethnic and sustainable clothing, handcrafted using traditional Indian techniques.",
                "img_path" => "/images/brands/fabindia.png"
            ],
            [
                "name" => "Biba",
                "description" => "Biba is a leading Indian women’s ethnic wear brand, famous for its vibrant prints and contemporary designs.",
                "img_path" => "/images/brands/biba.png"
            ],
            [
                "name" => "Jockey",
                "description" => "Jockey is a global innerwear and loungewear brand known for its comfort and quality.",
                "img_path" => "/images/brands/jockey.png"
            ],
            [
                "name" => "Van Heusen",
                "description" => "Van Heusen is a premium brand offering formal and smart-casual wear for men and women.",
                "img_path" => "/images/brands/van-heusen.png"
            ],
            [
                "name" => "UCB (United Colors of Benetton)",
                "description" => "UCB is a global fashion brand known for its colorful and contemporary clothing for men, women, and kids.",
                "img_path" => "/images/brands/ucb.png"
            ],
            [
                "name" => "Pantaloons",
                "description" => "Pantaloons is an Indian retail brand offering affordable fashion for the entire family.",
                "img_path" => "/images/brands/pantaloons.png"
            ],
            [
                "name" => "Red Tape",
                "description" => "Red Tape is a footwear and apparel brand known for its premium leather shoes and casual wear.",
                "img_path" => "/images/brands/redtape.png"
            ],
            [
                "name" => "Hugo Boss",
                "description" => "German luxury fashion house known for sleek formalwear, suits, and premium casuals.",
                "img_path" => "/images/brands/hugo-boss.png"
            ],
            [
                "name" => "Calvin Klein",
                "description" => "Global brand offering minimalist underwear, jeans, and casual apparel.",
                "img_path" => "/images/brands/calvin-klein.png"
            ],
            [
                "name" => "Skechers",
                "description" => "Comfort-focused footwear and athleisure wear for casual and fitness use.",
                "img_path" => "/images/brands/skechers.png"
            ],
            [
                "name" => "Decathlon",
                "description" => "Affordable sportswear and equipment for running, gym, and outdoor activities.",
                "img_path" => "/images/brands/decathlon.png"
            ],
            [
                "name" => "Manyavar",
                "description" => "Leading Indian brand for wedding and festive ethnic wear (men & women).",
                "img_path" => "/images/brands/manyavar.png"
            ],
            [
                "name" => "Raymond",
                "description" => "Raymond is a diversified group with majority business interests in Textile & Apparel sectors and a presence across varying segments such as Consumer Care, Realty and Engineering in national and international markets.",
                "img_path" => "/images/brands/raymond.png"
            ],
            [
                "name" => "Gini & Jony",
                "description" => "Popular Indian brand for trendy and affordable kids' fashion (0-14 years).",
                "img_path" => "/images/brands/gini-jony.png"
            ],
        ];

        foreach ($brands as $brand) {
            $existingBrand = ProductBrand::where('name', $brand['name'])->first();

            if (!$existingBrand) {
                $img_path = null;
                
                $sourcePath = public_path($brand['img_path']);
                
                if (File::exists($sourcePath)) {
                    $storagePath = 'brands/' . basename($brand['img_path']);
                    
                    if (!Storage::exists($storagePath)) {
                        Storage::put($storagePath, File::get($sourcePath));
                    }
                    
                    $img_path = $storagePath;
                }
                
                ProductBrand::create([
                    'name' => $brand['name'],
                    'description' => $brand['description'],
                    'img_path' => $img_path,
                ]);

            }
        }
          
    }
}
