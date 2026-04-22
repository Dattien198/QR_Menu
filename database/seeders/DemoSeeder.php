<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Branch;
use App\Models\RestaurantTable;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Nhà hàng demo ──────────────────────────────────────────────────
        $restaurant = Restaurant::updateOrCreate(
            ['slug' => 'vua-pho'],
            [
                'name'          => 'Vua Phở — Đệ Nhất Hà Thành',
                'address'       => '36 Hàng Muối, Hoàn Kiếm, Hà Nội',
                'contact_phone' => '02439876543',
                'contact_email' => 'contact@vuapho.vn',
                'vat'           => 8,
                'currency'      => 'VND',
                'theme_color'   => '#e85d04',
            ]
        );

        // ── 2. Admin account ──────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'          => 'Quản trị viên',
                'password'      => bcrypt('password'),
                'restaurant_id' => $restaurant->id,
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // Kitchen account
        $kitchen = User::firstOrCreate(
            ['email' => 'kitchen@admin.com'],
            [
                'name'          => 'Nhân viên bếp',
                'password'      => bcrypt('password'),
                'restaurant_id' => $restaurant->id,
            ]
        );
        if (!$kitchen->hasRole('kitchen')) {
            $kitchen->assignRole('kitchen');
        }

        // Cashier account
        $cashier = User::firstOrCreate(
            ['email' => 'cashier@admin.com'],
            [
                'name'          => 'Thu ngân',
                'password'      => bcrypt('password'),
                'restaurant_id' => $restaurant->id,
            ]
        );
        if (!$cashier->hasRole('cashier')) {
            $cashier->assignRole('cashier');
        }

        // ── 3. Chi nhánh (manager_id set after user exists) ───────────────────
        $branch = Branch::updateOrCreate(
            ['name' => 'Chi nhánh Hoàn Kiếm', 'restaurant_id' => $restaurant->id],
            [
                'address'    => '36 Hàng Muối, Hoàn Kiếm, Hà Nội',
                'phone'      => '02439876543',
                'manager_id' => $admin->id,
                'is_active'  => true,
            ]
        );

        // ── 4. Bàn + QR tokens ───────────────────────────────────────────────
        // Bàn demo với token cố định để dễ test
        $t1 = RestaurantTable::updateOrCreate(
            ['branch_id' => $branch->id, 'name' => 'Bàn 01'],
            ['capacity' => 4, 'status' => 'empty', 'qr_token' => 'table-token-1', 'floor' => 'Tầng 1', 'area' => 'Khu A']
        );
        $t1->generateQrCode();

        $tables = [
            ['name' => 'Bàn 02', 'capacity' => 4, 'floor' => 'Tầng 1', 'area' => 'Khu A'],
            ['name' => 'Bàn 03', 'capacity' => 4, 'floor' => 'Tầng 1', 'area' => 'Khu A'],
            ['name' => 'Bàn 04', 'capacity' => 2, 'floor' => 'Tầng 1', 'area' => 'Khu B'],
            ['name' => 'Bàn 05', 'capacity' => 6, 'floor' => 'Tầng 1', 'area' => 'Khu B'],
            ['name' => 'VIP 01', 'capacity' => 8, 'floor' => 'Tầng 2', 'area' => 'Khu VIP'],
            ['name' => 'VIP 02', 'capacity' => 8, 'floor' => 'Tầng 2', 'area' => 'Khu VIP'],
        ];
        foreach ($tables as $t) {
            $table = RestaurantTable::updateOrCreate(
                ['branch_id' => $branch->id, 'name' => $t['name']],
                ['capacity' => $t['capacity'], 'status' => 'empty', 'qr_token' => Str::random(24), 'floor' => $t['floor'], 'area' => $t['area']]
            );
            $table->generateQrCode();
        }

        // ── 5. Danh mục ───────────────────────────────────────────────────────
        $catData = [
            ['name' => 'Phở Truyền Thống',        'order' => 1],
            ['name' => 'Bún & Miến',              'order' => 2],
            ['name' => 'Món Khai Vị',              'order' => 3],
            ['name' => 'Món Ăn Kèm',             'order' => 4],
            ['name' => '🍵 Đồ Uống',             'order' => 5],
            ['name' => '🍮 Tráng Miệng',          'order' => 6],
        ];
        $cats = [];
        foreach ($catData as $c) {
            $cats[$c['name']] = Category::updateOrCreate(
                ['restaurant_id' => $restaurant->id, 'name' => $c['name']],
                ['order_index' => $c['order']]
            );
        }

        // ── 6. Món ăn ─────────────────────────────────────────────────────────
        $items = [
            // Phở
            ['cat' => 'Phở Truyền Thống', 'name' => 'Phở Bò Tái Lăn Đặc Biệt',  'desc' => 'Thịt bò bắp hoa xào tái với hành gừng, nước dùng béo ngậy đặc trưng từ xương ống ninh 12 tiếng.', 'price' => 85000, 'tags' => 'nổi bật,bò', 'featured' => true,  'cal' => 520, 'prep' => 10],
            ['cat' => 'Phở Truyền Thống', 'name' => 'Phở Bò Chín Nạm Gầu',       'desc' => 'Nạm bò mềm và gầu giòn tan, nước dùng trong veo được ninh từ xương ống ngũ vị hương.', 'price' => 75000, 'tags' => 'bò', 'featured' => false, 'cal' => 480, 'prep' => 8],
            ['cat' => 'Phở Truyền Thống', 'name' => 'Phở Gà Ta Chặt Miếng',     'desc' => 'Gà ta thả vườn thịt chắc, da giòn, ăn kèm với lá chanh thơm nồng và nước dùng gà thanh ngọt.', 'price' => 79000, 'tags' => 'gà,nổi bật', 'featured' => true,  'cal' => 440, 'prep' => 8],
            ['cat' => 'Phở Truyền Thống', 'name' => 'Phở Bò Tái Lăn (size lớn)', 'desc' => 'Phần lớn hơn 50% dành cho người ăn nhiều. Thịt bò tái mềm ngon, nước dùng đậm đà.', 'price' => 120000, 'tags' => 'bò,size lớn', 'featured' => false, 'cal' => 780, 'prep' => 10],
            ['cat' => 'Phở Truyền Thống', 'name' => 'Phở Chay Nấm Thập Cẩm',   'desc' => 'Dành cho thực khách ăn chay — nước dùng từ nấm hương và mía lau, thanh ngọt tự nhiên.', 'price' => 65000, 'tags' => 'chay,nấm', 'featured' => false, 'cal' => 320, 'prep' => 8],

            // Bún & Miến
            ['cat' => 'Bún & Miến', 'name' => 'Bún Bò Huế Cay Thơm',       'desc' => 'Nước dùng đậm đặc từ xả, ruốc sả, với thịt bò và chả Huế đặc trưng.', 'price' => 75000, 'tags' => 'cay,bò,Huế', 'featured' => true,  'cal' => 550, 'prep' => 8],
            ['cat' => 'Bún & Miến', 'name' => 'Miến Gà Truyền Thống',      'desc' => 'Miến dong trong, gà xé nhỏ, nấm mộc nhĩ và hành phi thơm lừng.', 'price' => 65000, 'tags' => 'gà', 'featured' => false, 'cal' => 390, 'prep' => 7],
            ['cat' => 'Bún & Miến', 'name' => 'Bún Chả Hà Nội',            'desc' => 'Chả viên và chả miếng nướng than hoa, ăn kèm bún, rau sống và nước dùng chua ngọt.', 'price' => 80000, 'tags' => 'nổi bật,Hà Nội', 'featured' => true,  'cal' => 600, 'prep' => 12],

            // Khai vị
            ['cat' => 'Món Khai Vị', 'name' => 'Nem Rán Hà Nội (5 chiếc)', 'desc' => 'Nhân thịt tôm mộc nhĩ giòn rụm, ăn kèm nước mắm chua ngọt và rau xà lách.', 'price' => 55000, 'tags' => 'nổi bật,chiên', 'featured' => true,  'cal' => 350, 'prep' => 10],
            ['cat' => 'Món Khai Vị', 'name' => 'Nộm Đu Đủ Tôm Thịt',       'desc' => 'Đu đủ bào sợi, tôm luộc, thịt ba rọi, ăn kèm bánh phồng tôm và nước chấm đặc biệt.', 'price' => 65000, 'tags' => 'salad,mát', 'featured' => false, 'cal' => 280, 'prep' => 8],
            ['cat' => 'Món Khai Vị', 'name' => 'Chả Giò Hải Sản',           'desc' => 'Nhân mực, tôm, cua hòa quyện, cuộn bánh tráng mỏng chiên vàng giòn.', 'price' => 75000, 'tags' => 'hải sản,chiên', 'featured' => false, 'cal' => 380, 'prep' => 12],

            // Ăn kèm
            ['cat' => 'Món Ăn Kèm', 'name' => 'Quẩy Nóng (2 chiếc)',      'desc' => 'Quẩy nứt vỏ, bên trong xốp mềm — không thể thiếu khi ăn phở.', 'price' => 15000, 'tags' => 'ăn kèm', 'featured' => false, 'cal' => 180, 'prep' => 3],
            ['cat' => 'Món Ăn Kèm', 'name' => 'Trứng Gà Lòng Đào',        'desc' => 'Trứng gà luộc lòng đào đặt trong bát phở, tăng thêm độ béo ngậy.', 'price' => 10000, 'tags' => 'ăn kèm', 'featured' => false, 'cal' => 90,  'prep' => 2],
            ['cat' => 'Món Ăn Kèm', 'name' => 'Rau Sống Thơm',             'desc' => 'Đĩa rau sống: giá, húng quế, ngò gai — ăn kèm các món bún phở.', 'price' => 10000, 'tags' => 'chay,ăn kèm', 'featured' => false, 'cal' => 40,  'prep' => 2],

            // Đồ uống
            ['cat' => '🍵 Đồ Uống', 'name' => 'Cà Phê Muối Đặc Biệt',    'desc' => 'Sự kết hợp hoàn hảo: vị đắng nhẹ của cà phê phin, lớp kem mặn mà và nước đường thốt nốt.', 'price' => 45000, 'tags' => 'cà phê,nổi bật', 'featured' => true,  'cal' => 220, 'prep' => 5],
            ['cat' => '🍵 Đồ Uống', 'name' => 'Trà Đào Cam Sả',           'desc' => 'Thức uống giải nhiệt sảng khoái: đào miếng, cam tươi và hương sả thơm mát.', 'price' => 45000, 'tags' => 'trà,mát,đông đá', 'featured' => false, 'cal' => 150, 'prep' => 3],
            ['cat' => '🍵 Đồ Uống', 'name' => 'Sinh Tố Bơ Tươi',          'desc' => 'Bơ chín mềm, sữa tươi và đường phèn — béo ngậy đúng kiểu miền Nam.', 'price' => 50000, 'tags' => 'sinh tố', 'featured' => false, 'cal' => 310, 'prep' => 5],
            ['cat' => '🍵 Đồ Uống', 'name' => 'Nước Lọc Aquafina',        'desc' => 'Nước khoáng tinh khiết 500ml.', 'price' => 15000, 'tags' => 'nước', 'featured' => false, 'cal' => 0,   'prep' => 1],

            // Tráng miệng
            ['cat' => '🍮 Tráng Miệng', 'name' => 'Chè Khúc Bạch Hạnh Nhân', 'desc' => 'Thạch kem sữa béo ngậy, hạnh nhân rang giòn, nước đường phèn hoặc cốt dừa thanh mát.', 'price' => 45000, 'tags' => 'ngọt,chè,nổi bật', 'featured' => true,  'cal' => 280, 'prep' => 3],
            ['cat' => '🍮 Tráng Miệng', 'name' => 'Bánh Flan Caramel',     'desc' => 'Bánh flan mềm mịn kiểu Việt, lớp caramel đắng nhẹ, ăn kèm đá viên nhỏ.', 'price' => 30000, 'tags' => 'ngọt,kem', 'featured' => false, 'cal' => 190, 'prep' => 2],
            ['cat' => '🍮 Tráng Miệng', 'name' => 'Kem Ốc Quế Matcha',    'desc' => 'Kem matcha Nhật Bản đậm vị, ốc quế giòn tan — kết thúc bữa ăn hoàn hảo.', 'price' => 35000, 'tags' => 'kem,matcha', 'featured' => false, 'cal' => 220, 'prep' => 2],
        ];

        foreach ($items as $item) {
            MenuItem::updateOrCreate(
                [
                    'category_id' => $cats[$item['cat']]->id,
                    'name'        => $item['name'],
                ],
                [
                    'description'      => $item['desc'],
                    'price'            => $item['price'],
                    'tags'             => $item['tags'],
                    'status'           => 'available',
                    'is_featured'      => $item['featured'],
                    'calories'         => $item['cal'],
                    'preparation_time' => $item['prep'],
                ]
            );
        }

        $this->command->info('✅ Demo data seeded! URL: /menu/vua-pho/table-token-1');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',   'admin@admin.com',   'password'],
                ['Kitchen', 'kitchen@admin.com', 'password'],
                ['Cashier', 'cashier@admin.com', 'password'],
            ]
        );
    }
}
