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
                ['id' => 5, 'nama_produk' => '70 Diamonds', 'harga' => 10000],
                ['id' => 6, 'nama_produk' => '140 Diamonds', 'harga' => 20000],
                ['id' => 7, 'nama_produk' => '355 Diamonds', 'harga' => 50000]
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
?>
