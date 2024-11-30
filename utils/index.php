<?php

function formatPrice($price)
{
    $parts = explode('|', $price);
    $amount = $parts[0];
    $currency = $parts[1] ?? '';

    return number_format((float) $amount, 2) . ($currency ? " $currency" : ' AED');
}

function getUsername($user_id)
{
    $users = [
        1 => "Angel Layug",
        8 => "Vortexweb",
        10 => "VortexWeb",
        14 => "Merced Licas",
        16 => "",
        18 => "Syed Zameer Hussain",
        31 => "Hatim Ayoub",
        33 => "Enwuma Mary Innocent",
        35 => "Danish Arora",
        37 => "Idris Malik",
        39 => "Maria Wu",
        41 => "Priyanka Sherwani",
        43 => "Yasir Mirza",
        45 => "Mandeep Kaur",
        47 => "Ahmed Al Khatib",
        49 => "Lavina Nihalani",
        51 => "Ali Shahama",
        53 => "Daniela Pires",
        55 => "Kanwal Adnan",
        57 => "Mohammed Ansab",
        59 => "Karshni Ahanta",
        61 => "Laxmi Nair",
        63 => "Raja Muhammad Muhammad",
        65 => "Abdoli Golibjon",
        67 => "Adilet Chynystanov",
        69 => "Ali Memon",
        71 => "Ali Tanveer Mirza",
        73 => "Amir Abbaszadeh",
        75 => "Amir Abbasi",
        77 => "Amir Yousaf",
        79 => "Anjal Singhvi",
        81 => "Ankita Joy Kurle",
        83 => "Aziz Hussaini",
        85 => "Babar Mushtaq",
        87 => "Bilal Kamal",
        89 => "Bipin Khanna",
        91 => "Ejura Adaji",
        93 => "Faisal Afzal",
        95 => "Farhan Khan Khan",
        97 => "Fuad Ahmad",
        99 => "Fyham Rai",
        101 => "Hamza Jangda",
        103 => "Hassan Aljanabi",
        105 => "Hassan Bin Khalid",
        107 => "Hassan Malik",
        109 => "Irshad Ahmad",
        111 => "Jad Abou",
        113 => "Mahwish Awan",
        115 => "Masoud Nasri",
        117 => "Mohammed Aadil",
        119 => "Mohammed Sufiyan",
        121 => "Naghmeh Sabet",
        123 => "Naif Khan",
        125 => "Neethu Murali",
        127 => "Omar Ahmed Hasan",
        129 => "Omid Zare",
        131 => "Rafeek Rasni",
        133 => "Robina Mushtaq",
        135 => "Safa Elshaikh",
        137 => "Sajad Najafi",
        139 => "Shahed Al Marouf",
        141 => "Sonia Gulistani",
        143 => "Taghrid Ibrahim",
        145 => "Talha Bin Akbar",
        147 => "Tauseef Rehman",
        149 => "Usman Rasheed",
        151 => "Wael Harrouk",
        153 => "Yaqoob Sattar",
        155 => "Yasmin Hamed Ahmed Elwarraki",
        157 => "Zaid Sangani",
        159 => "Areeb Kapadia",
        161 => "Aygun Aghakishiyeva",
        163 => "Mehroz Majeed",
        165 => "Murtuzal Iqbal",
        167 => "Ranusha De Silva",
        169 => "Sahil Mendiratta",
        171 => "Shafan Cader",
        173 => "Sonia Harjani",
        175 => "Swati Agrawal",
        177 => "Mohamed Barahmeh",
        179 => "Mohammed Saad",
        181 => "Imran Ashraf",
        183 => "Amani Zara",
        185 => "Ramin Kalantari",
        187 => "Sonia Baig",
        191 => "Ioana Petecuta",
        193 => "Fengqiao Yan",
        195 => "Priyanka Dev",
        197 => "Silviaceline Oparaocha",
        199 => "Adil Sayed",
        201 => "Ahmed Godil",
        203 => "Aleena Bhatti",
        205 => "Alina Sviridova",
        207 => "Basem Ghazi",
        209 => "Beatris Lastre",
        211 => "Tinuola Monique",
        215 => "Haroon Saleem",
        217 => "Nadeem Darvesh",
        219 => "Ola Hassan",
        221 => "Sarah Nafeh",
        223 => "Tina Elahi",
        225 => "Basit Ali",
        227 => "Faisal Hayat",
        229 => "Hammad Ali Lodhi",
        231 => "Shehrose Maqbool",
        233 => "Zhanyla Isakova",
        235 => "Mubeen Iqbal",
        237 => "Muhammad Granny",
        239 => "Muhammed Afraz",
        241 => "Eric Domingo",
        243 => "John Paul Macawile",
        245 => "JM Tupas",
        247 => "Euan San Miguel",
        249 => "Buhari Sayed",
        251 => "Zameer Hussain",
        253 => "Chetan Kapadnis",
        255 => "Jared Daniel Tolentino",
        257 => "Waleed Akhtar Akhtar",
        259 => "Ammer Afaq",
        261 => "Jonbon Abet",
        263 => "Madushika Kudagodage",
        265 => "Kaye Layson",
        267 => "Natallia Panaskina",
        269 => "Masroor Ahmed Syed",
        271 => "Shaista Naz Syed",
        273 => "Farooq Syed",
        275 => "Osman Syed",
        277 => "Abdullah Syed",
        279 => "Anne Lacad",
        281 => "Eloisa Castro",
        285 => "Aaryan",
        289 => "Angel Layug",
        339 => "Bitrix Agent Access DEMO",
        365 => "Aung Ko Phyo",
    ];

    return $users[$user_id];
}
