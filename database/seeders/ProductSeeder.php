<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        // for ($i = 1; $i <= 6; $i++) {
        //     Product::create([
        //         "product_name" => "Product $i",
        //         "orientation" => "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Adipisci porro debitis eius deserunt odio, repudiandae ad repellendus laboriosam nobis sed?",
        //         "description" => "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptatem temporibus, pariatur, tempore quia officiis at repudiandae dolore assumenda sunt fugiat alias illo nam minus autem dolor voluptate. Dignissimos eum natus ipsum optio neque numquam, voluptatem autem! Officiis, voluptas. Dolorum atque minima, aliquam facilis minus exercitationem aliquid doloremque vero, error qui consequatur quas tempore aspernatur asperiores cupiditate similique? Eius esse excepturi repellat deleniti, asperiores quas magni! Labore facere dicta expedita natus quisquam eaque, aspernatur minima quas nobis mollitia soluta sed id incidunt consequatur recusandae. Asperiores distinctio cum recusandae, odit earum quod vero similique assumenda? Autem perferendis ipsa accusamus id eaque. Sapiente!",
        //         "price" => rand(5000, 30000),
        //         "stock" => rand(10, 100),
        //         "discount" => 0.05,
        //         "image" => env("IMAGE_PRODUCT"),
        //     ]);
        // }
        // gaamRDJEO5xNbQMfgSXx91ZNIVYxid2S110yVkKg.jpg

        Product::create([
            "product_name" => "ASUS ROG Zephyrus G14",
            "category" => "new_laptop",
            "orientation" => "Laptop gaming premium dengan performa tinggi, ideal untuk gamer dan kreator konten.",
            "description" => "
                Spesifikasi ASUS ROG Zephyrus G14:
                - Prosesor: AMD Ryzen 9 7940HS
                - Kartu Grafis: NVIDIA GeForce RTX 4060, 8GB GDDR6  
                - RAM: 16GB DDR5 (upgradeable hingga 32GB)  
                - Penyimpanan: 1TB PCIe 4.0 NVMe SSD  
                - Layar: 14-inch QHD, 165Hz, 100% DCI-P3, Pantone Validated  
                - Sistem Operasi: Windows 11 Home  
                - Baterai: 4-cell 76WHr (hingga 10 jam penggunaan ringan)  
                - Port: USB-C (2x), USB-A (2x), HDMI 2.1, 3.5mm Audio Jack  
                - Konektivitas: WiFi 6E, Bluetooth 5.2  
                - Fitur Tambahan: AniMe Matrix LED Display di cover, sistem pendingin ROG Intelligent Cooling, dan Dolby Atmos Audio.  
            ",
            "buy_price" => 20000000,
            "sell_price" => 25000000,
            "stock" => 50,
            "discount" => 10,
            "image" => "product/ASUS_ROG_Zephyrus_G14.jpg",
            "created_by" => 1,
            "updated_by" => null,
        ]);

        Product::create([
            "product_name" => "Dell XPS 13 Plus",
            "category" => "new_laptop",
            "orientation" => "Ultrabook premium untuk produktivitas tinggi.",
            "description" => "
            Spesifikasi:
            - Prosesor: Intel Core i7-1360P  
            - RAM: 16GB LPDDR5  
            - Penyimpanan: 512GB NVMe SSD  
            - Layar: 13.4-inch OLED 3.5K Touchscreen  
            - Baterai: 10 jam penggunaan ringan   
            ",
            "buy_price" => 20000000,
            "sell_price" => 24000000,
            "stock" => 40,
            "discount" => 5,
            "image" => "product/Dell_XPS_13_Plus.jpg",
            "created_by" => 1,
            "updated_by" => null,
        ]);

        Product::create([
            "product_name" => "MacBook Pro 14-inch M2 Pro",
            "category" => "new_laptop",
            "orientation" => "Laptop premium dari Apple dengan chip M2 Pro.",
            "description" => "
            Spesifikasi:
            - Chipset: Apple M2 Pro (10-core CPU, 16-core GPU)  
            - RAM: 16GB Unified Memory  
            - Penyimpanan: 512GB SSD  
            - Layar: 14.2-inch Liquid Retina XDR  
            - Baterai: Hingga 17 jam  
            ",
            "buy_price" => 24000000,
            "sell_price" => 28000000,
            "stock" => 30,
            "discount" => 7,
            "image" => "product/MacBook_Pro_14-inch_M2_Pro.jpg",
            "created_by" => 1,
            "updated_by" => null,
        ]);

        Product::create([
            "product_name" => "HP Spectre x360 14",
            "category" => "new_laptop",
            "orientation" => "Laptop 2-in-1 dengan desain elegan.",
            "description" => "
            Spesifikasi:
            - Prosesor: Intel Core i7-1260P  
            - RAM: 16GB LPDDR4x  
            - Penyimpanan: 1TB NVMe SSD  
            - Layar: 13.5-inch OLED 3K Touchscreen  
            - Fitur: Pena Stylus, WiFi 6E  
            ",
            "buy_price" => 20000000,
            "sell_price" => 23000000,
            "stock" => 25,
            "discount" => 8,
            "image" => "product/HP_Spectre_x360.jpeg",
            "created_by" => 1,
            "updated_by" => null,
        ]);

        Product::create([
            "product_name" => "Lenovo Legion 5 Pro",
            "category" => "new_laptop",
            "orientation" => "Laptop gaming dengan performa tinggi.",
            "description" => "
            Spesifikasi:
            - Prosesor: AMD Ryzen 7 6800H  
            - Kartu Grafis: NVIDIA GeForce RTX 3070 Ti  
            - RAM: 16GB DDR5  
            - Penyimpanan: 512GB NVMe SSD  
            - Layar: 16-inch WQXGA, 165Hz  
            ",
            "buy_price" => 18000000,
            "sell_price" => 22000000,
            "stock" => 60,
            "discount" => 10,
            "image" => "product/Lenovo_Legion_5_Pro.jpeg",
            "created_by" => 1,
            "updated_by" => null,
        ]);

        Product::create([
            "product_name" => "Acer Swift 3 OLED",
            "category" => "new_laptop",
            "orientation" => "Laptop ringan dengan layar OLED.",
            "description" => "
            Spesifikasi:
            - Prosesor: Intel Core i5-1240P  
            - RAM: 16GB LPDDR5  
            - Penyimpanan: 512GB SSD  
            - Layar: 14-inch 2.8K OLED  
            - Baterai: Hingga 12 jam  
            ",
            "buy_price" => 12000000,
            "sell_price" => 15000000,
            "stock" => 70,
            "discount" => 5,
            "image" => "product/Acer_Swift_3_OLED.jpg",
            "created_by" => 1,
            "updated_by" => null,
        ]);

        Product::create([
            "product_name" => "ASUS TUF Gaming F15",
            "category" => "new_laptop",
            "orientation" => "Laptop gaming tangguh dengan harga terjangkau.",
            "description" => "
            Spesifikasi:
            - Prosesor: Intel Core i7-12700H  
            - Kartu Grafis: NVIDIA GeForce RTX 3050  
            - RAM: 16GB DDR4  
            - Penyimpanan: 512GB SSD  
            - Layar: 15.6-inch FHD, 144Hz  
            ",
            "buy_price" => 14000000,
            "sell_price" => 17000000,
            "stock" => 80,
            "discount" => 15,
            "image" => "product/ASUS_TUF_F15.jpg",
            "created_by" => 1,
            "updated_by" => null,
        ]);

        Product::create([
        "product_name" => "Razer Blade 15 Advanced",
        "category" => "second_laptop",
        "orientation" => "Laptop gaming premium dengan desain ramping.",
        "description" => "
            Spesifikasi:
            - Prosesor: Intel Core i7-12800H  
            - Kartu Grafis: NVIDIA GeForce RTX 3070 Ti  
            - RAM: 16GB DDR5  
            - Penyimpanan: 1TB SSD  
            - Layar: 15.6-inch QHD, 240Hz  
        ",
        "buy_price" => 28000000,
        "sell_price" => 30000000,
        "stock" => 20,
        "discount" => 12,
        "image" => "product/Razer_Blade_15.jpg",
        "created_by" => 1,
        "updated_by" => null,
        ]);

        Product::create([
        "product_name" => "Microsoft Surface Laptop 5",
        "category" => "second_laptop",
        "orientation" => "Laptop ultrabook untuk produktivitas tinggi.",
        "description" => "
            Spesifikasi:
            - Prosesor: Intel Core i7-1265U  
            - RAM: 16GB LPDDR5  
            - Penyimpanan: 512GB SSD  
            - Layar: 13.5-inch PixelSense Display, 3:2  
            - Baterai: Hingga 18 jam  
        ",
        "buy_price" => 18000000,
        "sell_price" => 20500000,
        "stock" => 40,
        "discount" => 7,
        "image" => "product/Microsoft_Surface_Laptop_5.jpeg",
        "created_by" => 1,
        "updated_by" => null,
        ]);

        Product::create([
        "product_name" => "Acer Predator Helios 300",
        "category" => "second_laptop",
        "orientation" => "Laptop gaming untuk performa berat.",
        "description" => "
            Spesifikasi:
            - Prosesor: Intel Core i7-13700H  
            - Kartu Grafis: NVIDIA GeForce RTX 4060  
            - RAM: 16GB DDR5  
            - Penyimpanan: 1TB SSD  
            - Layar: 15.6-inch QHD, 165Hz  
        ",
        "buy_price" => 21000000,
        "sell_price" => 24000000,
        "stock" => 30,
        "discount" => 10,
        "image" => "product/Acer_Predator_Helios_300.jpg",
        "created_by" => 1,
        "updated_by" => null,
        ]);

        Product::create([
        "product_name" => "ASUS VivoBook Pro 16X",
        "category" => "new_laptop",
        "orientation" => "Laptop untuk kreator konten.",
        "description" => "
            Spesifikasi:
            - Prosesor: AMD Ryzen 9 6900HX  
            - Kartu Grafis: NVIDIA GeForce RTX 3050 Ti  
            - RAM**: 16GB DDR5  
            - Penyimpanan: 1TB SSD  
            - Layar: 16-inch 4K OLED HDR  
        ",
        "buy_price" => 18000000,
        "sell_price" => 23000000,
        "stock" => 45,
        "discount" => 8,
        "image" => "product/ASUS_VivoBook_Pro_16X.png",
        "created_by" => 1,
        "updated_by" => null,
         ]);

        Product::create([
        "product_name" => "Lenovo ThinkPad X1 Carbon Gen 11",
        "category" => "new_laptop",
        "orientation" => "Ultrabook premium untuk profesional.",
        "description" => "
            Spesifikasi:
            - Prosesor: Intel Core i7-1365U  
            - RAM: 16GB LPDDR5  
            - Penyimpanan: 512GB SSD  
            - Layar: 14-inch WUXGA Touchscreen  
            - Baterai: Hingga 15 jam  
        ",
        "buy_price" => 22500000,
        "sell_price" => 26000000,
        "stock" => 35,
        "discount" => 10,
        "image" => "product/Lenovo_ThinkPad_X1_Carbon.jpg",
        "created_by" => 1,
        "updated_by" => null,
        ]);

        Product::create([
        "product_name" => "MSI Katana GF66",
        "category" => "new_laptop",
        "orientation" => "Laptop gaming dengan harga terjangkau.",
        "description" => "
            Spesifikasi:
            - Prosesor: Intel Core i5-12500H  
            - Kartu Grafis**: NVIDIA GeForce RTX 3050 Ti  
            - RAM: 8GB DDR4  
            - Penyimpanan: 512GB SSD  
            - Layar: 15.6-inch FHD, 144Hz  
        ",
        "buy_price" => 12700000,
        "sell_price" => 15000000,
        "stock" => 50,
        "discount" => 12,
        "image" => "product/MSI_Katana_GF66.jpg",
        "created_by" => 1,
        "updated_by" => null,
        ]);

        Product::create([
        "product_name" => "Dell Alienware x14",
        "category" => "second_laptop",
        "orientation" => "Laptop gaming tipis dan ringan.",
        "description" => "
            Spesifikasi:
            - Prosesor: Intel Core i7-12700H  
            - Kartu Grafis: NVIDIA GeForce RTX 3060  
            - RAM: 16GB DDR5  
            - Penyimpanan: 1TB SSD  
            - Layar: 14-inch FHD, 144Hz  
        ",
        "buy_price" => 24000000,
        "sell_price" => 28000000,
        "stock" => 25,
        "discount" => 10,
        "image" => "product/Dell_Alienware_x14.jpg",
        "created_by" => 1,
        "updated_by" => null,
        ]);

        Product::create([
        "product_name" => "HP Omen 16",
        "category" => "second_laptop",
        "orientation" => "Laptop gaming dengan layar besar.",
        "description" => "
            Spesifikasi:
            - Prosesor: AMD Ryzen 7 6800H  
            - Kartu Grafis: NVIDIA GeForce RTX 3070  
            - RAM: 16GB DDR5  
            - Penyimpanan: 1TB SSD  
            - Layar: 16.1-inch QHD, 165Hz  
        ",
        "buy_price" => 19000000,
        "sell_price" => 21000000,
        "stock" => 40,
        "discount" => 9,
        "image" => "product/HP_Omen_16.png",
        "created_by" => 1,
        "updated_by" => null,
        ]);

        Product::create([
        "product_name" => "Gigabyte Aero 16",
        "category" => "second_laptop",
        "orientation" => "Laptop kreator dengan layar OLED.",
        "description" => "
            Spesifikasi:
            - Prosesor: Intel Core i9-12900H  
            - Kartu Grafis: NVIDIA GeForce RTX 3070 Ti  
            - RAM: 32GB DDR5  
            - Penyimpanan: 1TB SSD  
            - Layar: 16-inch 4K AMOLED  
        ",
        "buy_price" => 27400000,
        "sell_price" => 29600000,
        "stock" => 15,
        "discount" => 8,
        "image" => "product/Gigabyte_Aero_16.jpeg",
        "created_by" => 1,
        "updated_by" => null,
        ]);

        Product::create([
        "product_name" => "ASUS ZenBook Duo 14",
        "category" => "new_laptop",
        "orientation" => "Laptop dengan dua layar untuk multitasking.",
        "description" => "
            Spesifikasi:
            - Prosesor: Intel Core i7-12500H  
            - RAM**: 16GB LPDDR5  
            - Penyimpanan: 1TB SSD  
            - Layar: 14-inch FHD + ScreenPad Plus  
        ",
        "buy_price" => 22900000,
        "sell_price" => 24000000,
        "stock" => 30,
        "discount" => 10,
        "image" => "product/ASUS_ZenBook_Duo_14.png",
        "created_by" => 1,
        "updated_by" => null,
        ]);

        Product::create([
        "product_name" => "Razer Book 13",
        "category" => "others",
        "orientation" => "Ultrabook untuk produktivitas dengan desain ramping.",
        "description" => "
            Spesifikasi:
            - Prosesor: Intel Core i7-1165G7  
            - RAM: 16GB LPDDR4x  
            - Penyimpanan: 512GB SSD  
            - Layar: 13.4-inch UHD Touchscreen  
        ",
        "buy_price" => 19000000,
        "sell_price" => 21000000,
        "stock" => 45,
        "discount" => 5,
        "image" => "product/Razer_Book_13.jpg",
        "created_by" => 1,
        "updated_by" => null,
        ]);

        Product::create([
        "product_name" => "Apple MacBook Air M2",
        "category" => "others",
        "orientation" => "Laptop ultrabook dengan performa efisien.",
        "description" => "
            Spesifikasi:
            - Chipset: Apple M2 (8-core CPU, 8-core GPU)  
            - RAM: 8GB Unified Memory  
            - Penyimpanan**: 512GB SSD  
            - Layar: 13.6-inch Liquid Retina  
        ",
        "buy_price" => 18000000,
        "sell_price" => 19000000,
        "stock" => 50,
        "discount" => 6,
        "image" => "product/MacBook_Air_M2.jpeg",
        "created_by" => 1,
        "updated_by" => null,
        ]);

        Product::create([
            "product_name" => "Logitech G203",
            "category" => "others",
            "orientation" => "Wired Mouse.",
            "description" => "
                Mouse dengan tampilan menarik, memakai kabel.  
            ",
            "buy_price" => 100000,
            "sell_price" => 150000,
            "stock" => 50,
            "discount" => 0,
            "image" => "product/Logitech.jpg",
            "created_by" => 1,
            "updated_by" => null,
            ]);

            Product::create([
                "product_name" => "Sandisk 64GB",
                "category" => "others",
                "orientation" => "Flashdisk terbaik.",
                "description" => "
                    Flashdisk dengan penyimpanan yang banyak
                ",
                "buy_price" => 120000,
                "sell_price" => 180000,
                "stock" => 60,
                "discount" => 0,
                "image" => "product/Sandisk_64GB.jpg",
                "created_by" => 1,
                "updated_by" => null,
                ]);

    }
}
