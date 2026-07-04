<?php
// UNTUK MENAMBAH ATAU MENGUBAH GAME, NOMINAL TOP UP/HARGA PRODUK, DAN METODE PEMBAYARAN:
// Edit data array di bawah ini. Pastikan slug game unik dan sinkron dengan index.php.
return [
    'fallback_games' => [
        [
            'id' => 1,
            'nama_game' => 'Mobile Legends',
            'slug' => 'mobile-legends',
            'deskripsi' => 'Top up Diamond Mobile Legends termurah dan tercepat hanya dalam hitungan detik.',
            'status' => 'aktif'
        ],
        [
            'id' => 2,
            'nama_game' => 'Free Fire',
            'slug' => 'free-fire',
            'deskripsi' => 'Top up Diamond Free Fire untuk membeli elite pass dan bundle favoritmu.',
            'status' => 'aktif'
        ],
        [
            'id' => 3,
            'nama_game' => 'PUBG Mobile',
            'slug' => 'pubg-mobile',
            'deskripsi' => 'Top up UC PUBG Mobile termurah untuk skin keren dan Royale Pass.',
            'status' => 'aktif'
        ],
        [
            'id' => 4,
            'nama_game' => 'Genshin Impact',
            'slug' => 'genshin-impact',
            'deskripsi' => 'Top up Genesis Crystals Genshin Impact untuk gacha karakter impianmu.',
            'status' => 'aktif'
        ]
    ],
    'mock_games' => [
        'mobile-legends' => [
            'id' => 1,
            'nama_game' => 'Mobile Legends',
            'deskripsi' => 'Top up Diamond Mobile Legends termurah dan tercepat hanya dalam hitungan detik.',
            'produk' => [
                ['id' => 1, 'nama_produk' => '86 Diamonds', 'harga' => 20000],
                ['id' => 2, 'nama_produk' => '172 Diamonds', 'harga' => 40000],
                ['id' => 3, 'nama_produk' => '257 Diamonds', 'harga' => 60000],
                ['id' => 4, 'nama_produk' => '706 Diamonds', 'harga' => 150000]
            ]
        ],
        'free-fire' => [
            'id' => 2,
            'nama_game' => 'Free Fire',
            'deskripsi' => 'Top up Diamond Free Fire untuk membeli elite pass dan bundle favoritmu.',
            'produk' => [
                ['id' => 201, 'nama_produk' => 'Member Mingguan', 'harga' => 30008],
                ['id' => 202, 'nama_produk' => 'Member Bulanan', 'harga' => 89951],
                ['id' => 51, 'nama_produk' => '5 Diamonds', 'harga' => 974],
                ['id' => 52, 'nama_produk' => '12 Diamonds', 'harga' => 1948],
                ['id' => 53, 'nama_produk' => '15 Diamonds', 'harga' => 2922],
                ['id' => 54, 'nama_produk' => '20 Diamonds', 'harga' => 3895],
                ['id' => 55, 'nama_produk' => '25 Diamonds', 'harga' => 4869],
                ['id' => 56, 'nama_produk' => '30 Diamonds', 'harga' => 5843],
                ['id' => 57, 'nama_produk' => '50 Diamonds', 'harga' => 7787],
                ['id' => 58, 'nama_produk' => '55 Diamonds', 'harga' => 8761],
                ['id' => 59, 'nama_produk' => '70 Diamonds', 'harga' => 9733],
                ['id' => 60, 'nama_produk' => '75 Diamonds', 'harga' => 10707],
                ['id' => 61, 'nama_produk' => '80 Diamonds', 'harga' => 11681],
                ['id' => 62, 'nama_produk' => '90 Diamonds', 'harga' => 13628],
                ['id' => 63, 'nama_produk' => '95 Diamonds', 'harga' => 14602],
                ['id' => 64, 'nama_produk' => '100 Diamonds', 'harga' => 15575],
                ['id' => 65, 'nama_produk' => '120 Diamonds', 'harga' => 17520],
                ['id' => 66, 'nama_produk' => '125 Diamonds', 'harga' => 18493],
                ['id' => 67, 'nama_produk' => '140 Diamonds', 'harga' => 19465],
                ['id' => 68, 'nama_produk' => '145 Diamonds', 'harga' => 20439],
                ['id' => 69, 'nama_produk' => '150 Diamonds', 'harga' => 21412],
                ['id' => 70, 'nama_produk' => '160 Diamonds', 'harga' => 23360],
                ['id' => 71, 'nama_produk' => '170 Diamonds', 'harga' => 25307],
                ['id' => 72, 'nama_produk' => '190 Diamonds', 'harga' => 27252],
                ['id' => 73, 'nama_produk' => '210 Diamonds', 'harga' => 29198],
                ['id' => 74, 'nama_produk' => '230 Diamonds', 'harga' => 33092],
                ['id' => 75, 'nama_produk' => '260 Diamonds', 'harga' => 36984],
                ['id' => 76, 'nama_produk' => '280 Diamonds', 'harga' => 38929],
                ['id' => 77, 'nama_produk' => '300 Diamonds', 'harga' => 42824],
                ['id' => 78, 'nama_produk' => '355 Diamonds', 'harga' => 48661],
                ['id' => 79, 'nama_produk' => '360 Diamonds', 'harga' => 49635],
                ['id' => 80, 'nama_produk' => '375 Diamonds', 'harga' => 52556],
                ['id' => 81, 'nama_produk' => '405 Diamonds', 'harga' => 56447],
                ['id' => 82, 'nama_produk' => '425 Diamonds', 'harga' => 58394],
                ['id' => 83, 'nama_produk' => '455 Diamonds', 'harga' => 64236],
                ['id' => 84, 'nama_produk' => '475 Diamonds', 'harga' => 66180],
                ['id' => 85, 'nama_produk' => '495 Diamonds', 'harga' => 68125],
                ['id' => 86, 'nama_produk' => '500 Diamonds', 'harga' => 69099],
                ['id' => 87, 'nama_produk' => '512 Diamonds', 'harga' => 71046],
                ['id' => 88, 'nama_produk' => '515 Diamonds', 'harga' => 72020],
                ['id' => 89, 'nama_produk' => '520 Diamonds', 'harga' => 72994],
                ['id' => 90, 'nama_produk' => '545 Diamonds', 'harga' => 75912],
                ['id' => 91, 'nama_produk' => '565 Diamonds', 'harga' => 77858],
                ['id' => 92, 'nama_produk' => '600 Diamonds', 'harga' => 84674]
            ]
        ],
        'pubg-mobile' => [
            'id' => 3,
            'nama_game' => 'PUBG Mobile',
            'deskripsi' => 'Top up UC PUBG Mobile termurah untuk skin keren dan Royale Pass.',
            'produk' => [
                ['id' => 8, 'nama_produk' => '60 UC', 'harga' => 15000],
                ['id' => 9, 'nama_produk' => '325 UC', 'harga' => 75000]
            ]
        ],
        'genshin-impact' => [
            'id' => 4,
            'nama_game' => 'Genshin Impact',
            'deskripsi' => 'Top up Genesis Crystals Genshin Impact untuk gacha karakter impianmu.',
            'produk' => [
                ['id' => 10, 'nama_produk' => '60 Genesis Crystals', 'harga' => 16000],
                ['id' => 11, 'nama_produk' => '300 Genesis Crystals', 'harga' => 79000]
            ]
        ]
    ],
    'mock_payments' => [
        ['id' => 1, 'nama' => 'DANA', 'kode' => 'DANA'],
        ['id' => 2, 'nama' => 'GoPay', 'kode' => 'GOPAY'],
        ['id' => 3, 'nama' => 'OVO', 'kode' => 'OVO'],
        ['id' => 4, 'nama' => 'Transfer Bank BCA', 'kode' => 'BCA']
    ]
];
