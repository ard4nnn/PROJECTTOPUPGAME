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
            'nama_game' => 'Wuthering Waves',
            'slug' => 'wuthering-waves',
            'deskripsi' => 'Top up Lunites Wuthering Waves untuk gacha karakter impianmu.',
            'status' => 'aktif'
        ]
    ],
    'mock_games' => [
        'mobile-legends' => [
            'id' => 1,
            'nama_game' => 'Mobile Legends',
            'deskripsi' => 'Top up Diamond Mobile Legends termurah dan tercepat hanya dalam hitungan detik.',
            'produk' => [
                // Memberships
                ['id' => 1001, 'nama_produk' => 'Weekly Diamond Pass', 'harga' => 29551],
                ['id' => 1002, 'nama_produk' => '2x Weekly Diamond Pass', 'harga' => 59102],
                ['id' => 1003, 'nama_produk' => '3x Weekly Diamond Pass', 'harga' => 88653],
                ['id' => 1004, 'nama_produk' => '4x Weekly Diamond Pass', 'harga' => 118204],
                ['id' => 1005, 'nama_produk' => '5x Weekly Diamond Pass', 'harga' => 147755],
                ['id' => 1006, 'nama_produk' => 'Twilight Pass', 'harga' => 152771],
                
                // Diamonds
                ['id' => 1007, 'nama_produk' => '5 (5+0) Diamonds', 'harga' => 1530],
                ['id' => 1008, 'nama_produk' => '10 (9+1) Diamonds', 'harga' => 3111],
                ['id' => 1009, 'nama_produk' => '12 (11+1) Diamonds', 'harga' => 3567],
                ['id' => 1010, 'nama_produk' => '14 (13+1) Diamonds', 'harga' => 4148],
                ['id' => 1011, 'nama_produk' => '15 (15+0) Diamonds', 'harga' => 4588],
                ['id' => 1012, 'nama_produk' => '17 (16+1) Diamonds', 'harga' => 4779],
                ['id' => 1013, 'nama_produk' => '18 (17+1) Diamonds', 'harga' => 5185],
                ['id' => 1014, 'nama_produk' => '19 (17+2) Diamonds', 'harga' => 5606],
                ['id' => 1015, 'nama_produk' => '20 (18+2) Diamonds', 'harga' => 6221],
                ['id' => 1016, 'nama_produk' => '22 (20+2) Diamonds', 'harga' => 6677],
                ['id' => 1017, 'nama_produk' => '28 (25+3) Diamonds', 'harga' => 8153],
                ['id' => 1018, 'nama_produk' => '30 (28+2) Diamonds', 'harga' => 8751],
                ['id' => 1019, 'nama_produk' => '33 (30+3) Diamonds', 'harga' => 9559],
                ['id' => 1020, 'nama_produk' => '36 (33+3) Diamonds', 'harga' => 10367],
                ['id' => 1021, 'nama_produk' => '44 (40+4) Diamonds', 'harga' => 12228],
                ['id' => 1022, 'nama_produk' => '45 (42+3) Diamonds', 'harga' => 13390],
                ['id' => 1023, 'nama_produk' => '46 (42+4) Diamonds', 'harga' => 13477],
                ['id' => 1024, 'nama_produk' => '50 (46+4) Diamonds', 'harga' => 14337],
                ['id' => 1025, 'nama_produk' => '54 (49+5) Diamonds', 'harga' => 15339],
                ['id' => 1026, 'nama_produk' => '56 (51+5) Diamonds', 'harga' => 15795],
                ['id' => 1027, 'nama_produk' => '59 (53+6) Diamonds', 'harga' => 16305],
                ['id' => 1028, 'nama_produk' => '60 (55+5) Diamonds', 'harga' => 17624],
                ['id' => 1029, 'nama_produk' => '64 (58+6) Diamonds', 'harga' => 17834],
                ['id' => 1030, 'nama_produk' => '67 (62+5) Diamonds', 'harga' => 18942],
                ['id' => 1031, 'nama_produk' => '71 (64+7) Diamonds', 'harga' => 19871],
                ['id' => 1032, 'nama_produk' => '74 (67+7) Diamonds', 'harga' => 20732],
                ['id' => 1033, 'nama_produk' => '78 (70+8) Diamonds', 'harga' => 21911],
                ['id' => 1034, 'nama_produk' => '80 (73+7) Diamonds', 'harga' => 22595],
                ['id' => 1035, 'nama_produk' => '85 (77+8) Diamonds', 'harga' => 23439],
                ['id' => 1036, 'nama_produk' => '88 (80+8) Diamonds', 'harga' => 24879],
                ['id' => 1037, 'nama_produk' => '89 (81+8) Diamonds', 'harga' => 25056],
                ['id' => 1038, 'nama_produk' => '92 (84+8) Diamonds', 'harga' => 25916],
                ['id' => 1039, 'nama_produk' => '98 (89+9) Diamonds', 'harga' => 27864],
                ['id' => 1040, 'nama_produk' => '100 (91+9) Diamonds', 'harga' => 28078],
                ['id' => 1041, 'nama_produk' => '110 (100+10) Diamonds', 'harga' => 31098],
                ['id' => 1042, 'nama_produk' => '113 (102+11) Diamonds', 'harga' => 31591],
                ['id' => 1043, 'nama_produk' => '116 (105+11) Diamonds', 'harga' => 33031],
                ['id' => 1044, 'nama_produk' => '129 (117+12) Diamonds', 'harga' => 35667],
                ['id' => 1045, 'nama_produk' => '148 (134+14) Diamonds', 'harga' => 41463],
                ['id' => 1046, 'nama_produk' => '170 (154+16) Diamonds', 'harga' => 46876],
                ['id' => 1047, 'nama_produk' => '176 (160+16) Diamonds', 'harga' => 49758],
                ['id' => 1048, 'nama_produk' => '184 (167+17) Diamonds', 'harga' => 53097],
                ['id' => 1049, 'nama_produk' => '222 (200+22) Diamonds', 'harga' => 62193],
                ['id' => 1050, 'nama_produk' => '240 (217+23) Diamonds', 'harga' => 66201],
                ['id' => 1051, 'nama_produk' => '241 (218+23) Diamonds', 'harga' => 67870],
                ['id' => 1052, 'nama_produk' => '277 (250+27) Diamonds', 'harga' => 77741],
                ['id' => 1053, 'nama_produk' => '284 (257+27) Diamonds', 'harga' => 78429],
                ['id' => 1054, 'nama_produk' => '296 (256+40) Diamonds', 'harga' => 81478],
                ['id' => 1055, 'nama_produk' => '305 (276+29) Diamonds', 'harga' => 86036],
                ['id' => 1056, 'nama_produk' => '370 (333+37) Diamonds', 'harga' => 103654],
                ['id' => 1057, 'nama_produk' => '384 (346+38) Diamonds', 'harga' => 107801],
                ['id' => 1058, 'nama_produk' => '408 (367+41) Diamonds', 'harga' => 112032],
                ['id' => 1059, 'nama_produk' => '518 (467+51) Diamonds', 'harga' => 145116],
                ['id' => 1060, 'nama_produk' => '568 (503+65) Diamonds', 'harga' => 152771],
                ['id' => 1061, 'nama_produk' => '716 (637+79) Diamonds', 'harga' => 194233],
                ['id' => 1062, 'nama_produk' => '750 (676+74) Diamonds', 'harga' => 203213],
                ['id' => 1063, 'nama_produk' => '790 (703+87) Diamonds', 'harga' => 214963],
                ['id' => 1064, 'nama_produk' => '875 (774+101) Diamonds', 'harga' => 234248],
                ['id' => 1065, 'nama_produk' => '966 (836+130) Diamonds', 'harga' => 259133],
                ['id' => 1066, 'nama_produk' => '1048 (936+112) Diamonds', 'harga' => 287522]
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
                // Elite Pass
                ['id' => 301, 'nama_produk' => 'Elite Pass', 'harga' => 186768],
                ['id' => 302, 'nama_produk' => 'Elite Pass Plus', 'harga' => 466917],

                // UC Nominal
                ['id' => 303, 'nama_produk' => '60 UC (ID)', 'harga' => 16902],
                ['id' => 304, 'nama_produk' => '120 UC (ID)', 'harga' => 33804],
                ['id' => 305, 'nama_produk' => '180 UC (ID)', 'harga' => 50706],
                ['id' => 306, 'nama_produk' => '240 UC (ID)', 'harga' => 67607],
                ['id' => 307, 'nama_produk' => '325 (300+25) UC (ID)', 'harga' => 84898],
                ['id' => 308, 'nama_produk' => '385 (360+25) UC (ID)', 'harga' => 101799],
                ['id' => 309, 'nama_produk' => '445 (420+25) UC (ID)', 'harga' => 118701],
                ['id' => 310, 'nama_produk' => '505 (480+25) UC (ID)', 'harga' => 135603],
                ['id' => 311, 'nama_produk' => '565 (540+25) UC (ID)', 'harga' => 152504],
                ['id' => 312, 'nama_produk' => '660 (600+60) UC (ID)', 'harga' => 169795],
                ['id' => 313, 'nama_produk' => '720 (660+60) UC (ID)', 'harga' => 186696],
                ['id' => 314, 'nama_produk' => '780 (720+60) UC (ID)', 'harga' => 203598],
                ['id' => 315, 'nama_produk' => '840 (780+60) UC (ID)', 'harga' => 220500],
                ['id' => 316, 'nama_produk' => '900 (840+60) UC (ID)', 'harga' => 237401],
                ['id' => 317, 'nama_produk' => '985 (900+85) UC (ID)', 'harga' => 254692],
                ['id' => 318, 'nama_produk' => '1105 (1020+85) UC (ID)', 'harga' => 288495],
                ['id' => 319, 'nama_produk' => '1165 (1080+85) UC (ID)', 'harga' => 305397],
                ['id' => 320, 'nama_produk' => '1320 (1200+120) UC (ID)', 'harga' => 339589],
                ['id' => 321, 'nama_produk' => '1500 (1380+120) UC (ID)', 'harga' => 390294],
                ['id' => 322, 'nama_produk' => '1800 (1500+300) UC (ID)', 'harga' => 424872],
                ['id' => 323, 'nama_produk' => '1920 (1620+300) UC (ID)', 'harga' => 458675],
                ['id' => 324, 'nama_produk' => '1980 (1680+300) UC (ID)', 'harga' => 475577],
                ['id' => 325, 'nama_produk' => '2125 (1800+325) UC (ID)', 'harga' => 509769],
                ['id' => 326, 'nama_produk' => '2460 (2100+360) UC (ID)', 'harga' => 594666],
                ['id' => 327, 'nama_produk' => '2785 (2400+385) UC (ID)', 'harga' => 679563],
                ['id' => 328, 'nama_produk' => '3120 (2700+420) UC (ID)', 'harga' => 764460],
                ['id' => 329, 'nama_produk' => '3850 (3000+850) UC (ID)', 'harga' => 849743],
                ['id' => 330, 'nama_produk' => '4030 (3180+850) UC (ID)', 'harga' => 900448],
                ['id' => 331, 'nama_produk' => '4175 (3300+875) UC (ID)', 'harga' => 934640],
                ['id' => 332, 'nama_produk' => '4510 (3600+910) UC (ID)', 'harga' => 1019537]
            ]
        ],

        'wuthering-waves' => [
            'id' => 4,
            'nama_game' => 'Wuthering Waves',
            'deskripsi' => 'Top up Lunites Wuthering Waves untuk gacha karakter impianmu.',
            'produk' => [
                // Lunite Subscription (kategori terpisah)
                ['id' => 400, 'nama_produk' => 'Lunite Subscription', 'harga' => 77513],

                // Lunites Top Up
                ['id' => 401, 'nama_produk' => '60 Lunites', 'harga' => 15267],
                ['id' => 402, 'nama_produk' => '300 + 30 Lunites', 'harga' => 76505],
                ['id' => 403, 'nama_produk' => '980 + 110 Lunites', 'harga' => 234116],
                ['id' => 404, 'nama_produk' => '1980 + 260 Lunites', 'harga' => 464857],
                ['id' => 405, 'nama_produk' => '3280 + 600 Lunites', 'harga' => 767700],
                ['id' => 406, 'nama_produk' => '6480 + 1600 Lunites', 'harga' => 1484052]
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
