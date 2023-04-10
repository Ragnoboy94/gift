<?php
function getOrthodoxEasterDate() {
    $year = date("Y");
    $a = $year % 4;
    $b = $year % 7;
    $c = $year % 19;
    $d = (19 * $c + 15) % 30;
    $e = (2 * $a + 4 * $b - $d + 34) % 7;
    $month = floor(($d + $e + 114) / 31);
    $day = (($d + $e + 114) % 31) + 1;
    $date = mktime(0, 0, 0, $month, $day + 13, $year);
    return date("m-d", $date);
}
return [
    [
        'id' => 1,
        'name' => 'Birthday',
        'date' => null,
        'image' => 'birthday.jpg',
        'description' => "Make the birthday truly unforgettable! Trust the gift choice to another user and receive a unique surprise.",
        'benefits' => [
            "Personalized approach to birthday gifts",
            "Save time on selecting and purchasing a gift",
            "Unexpectedness and delight from receiving a unique gift",
        ],
        'keywords' => ['birthday', 'birthday gift', 'birthday party', 'celebrate birthday'],
    ],
    [
        'id' => 2,
        'name' => 'New Year',
        'date' => '01-01',
        'image' => 'new_year.jpg',
        'description' => "A magical celebration deserves a magical gift! Allow another user to put together an extraordinary and thrilling gift.",
        'benefits' => [
            "No need to struggle with gift selection",
            "Festive atmosphere and enchantment for all participants",
            "Exclusive New Year's gifts chosen with heart",
            "Save time on searching for the perfect gift in stores",
        ],
        'keywords' => ['new year', 'new year gift', 'new year celebration', 'new year party'],
    ],
    [
        'id' => 3,
        'name' => 'Christmas',
        'date' => '01-07',
        'image' => 'christmas.jpg',
        'description' => "Fill the Christmas holiday with an atmosphere of love and kindness with a unique gift! Trust the choice to another user and turn the celebration into a family feast with genuine emotions.",
        'benefits' => [
            "Warm and heartfelt gifts that bring you closer to family traditions and festive mood",
            "Free yourself from the hassle and stress of choosing a gift",
            "Give your loved ones the joy and surprise of magical surprises",
        ],
        'keywords' => ['Christmas gifts', 'family', 'love', 'kindness', 'warmth', 'heartfelt gifts', 'traditions', 'festive mood', 'magical surprises', 'surprise', 'joy', 'originality', 'creativity','stress-free gift selection', 'family feast'],
    ],
    [
        'id' => 4,
        'name' => "Valentine's Day",
        'date' => '02-14', 'image' => 'valentines_day.jpg',
        'description' => "Ignite the spark of passion on Valentine's Day! Open your heart to love with a unique gift chosen by another user.",
        'benefits' => [
            "One-of-a-kind gifts that will bring you closer to your significant other",
            "Focus on your feelings while entrusting the gift selection to another user",
            "Surprise your loved one with an unexpected and heartfelt surprise",
        ],
        'keywords' => ['valentine', 'valentine gift', 'valentine day', 'love gift'],
    ],
    [
        'id' => 5,
        'name' => "Defender of the Fatherland Day",
        'date' => '02-23', 'image' => 'defender_day.jpg',
        'description' => "Give a special touch to courage and bravery on Defender of the Fatherland Day! Delight a close man with a unique gift chosen by another user.",
        'benefits' => [
            "Gifts that reflect pride and gratitude for defenders",
            "Leave the worries of gift selection and enjoy the celebration",
            "Highlight the significance of the holiday with an unusual gift",
        ],
        'keywords' => ['defender', 'defender gift', 'defender day', 'military gift'],
    ],
    [
        'id' => 6,
        'name' => "International Women's Day",
        'date' => '03-08',
        'image' => 'womens_day.jpg',
        'description' => "Celebrate March 8th with a special gift! Trust the choice to another user and give unforgettable impressions to your loved ones.",
        'benefits' => [
            "Gifts that will definitely delight and surprise women",
            "Save time on gift searching before the holiday",
            "Highlight individuality and attention to detail",
        ],
        'keywords' => ['women\'s day', '8th of march', 'march 8', 'women\'s gift', 'women\'s day gift'],
    ],
    [
        'id' => 7,
        'name' => 'Easter',
        'date' => getOrthodoxEasterDate(),
        'image' => 'easter.jpg',
        'description' => "Bring magic and joy to the Easter celebration with a unique gift! Open your heart to faith and love with a surprise chosen by another user.",
        'benefits' => [
            "Creative gifts that will add festive atmosphere and adorn your Easter table",
            "Free yourself from the agonizing gift choice by trusting the process to another user",
            "Make the Easter celebration unforgettable with original and bright surprises",
        ],
        'keywords' => ['easter', 'easter gift', 'easter celebration', 'easter present'],
    ],
    [
        'id' => 8,
        'name' => 'Wedding Anniversary',
        'date' => null,
        'image' => 'wedding_anniversary.jpg',
        'description' => "Turn your wedding anniversary into an unforgettable event with a unique gift! Allow another user to choose a surprise that brings a fresh perspective to your shared happiness.",
        'benefits' => [
            "Sincere and meaningful gifts that reflect your love and strong relationship",
            "Save time on gift selection and devote more time to your special evening",
            "Surprise your better half with new experiences and warm emotions",
        ],
        'keywords' => ['wedding anniversary', 'anniversary gift', 'wedding gift', 'romantic gift'],
    ],
    [
        'id' => 9,
        'name' => 'Mother\'s Day',
        'date' => date('m-d', strtotime('last Sunday of November')),
        'image' => 'mothers_day.jpg',
        'description' => "Make Mother's Day unforgettable with a special gift! Express your gratitude and love for your mom with a surprise chosen by another user.",
        'benefits' => [
            "Warm and caring gifts that tell your mom how special she is",
            "Let another user embody your feelings in a tangible surprise",
            "Make this holiday unforgettable for your mom with original ideas",
        ],
        'keywords' => ['mother\'s day', 'mom gift', 'mother\'s day present', 'mother\'s day celebration'],
    ],
    [
        'id' => 10,
        'name' => 'Teacher\'s Day',
        'date' => '10-05',
        'image' => 'teachers_day.jpg',
        'description' => "Celebrate Teacher's Day by paying tribute to those who ignite the spark of knowledge in our hearts! Entrust the gift choice to another user and surprise the teacher with an original surprise.",
        'benefits' => [
            "Creative and meaningful gifts that convey your recognition and respect",
            "Relieve the stress of choosing a gift and focus on gratitude",
            "Help create bright memories and joy on the faces of teachers",
        ],
        'keywords' => ['teacher gifts', 'appreciation', 'recognition', 'gratitude', 'creativity', 'originality', 'surprise', 'memorable gifts', 'joy', 'happy memories', 'meaningful gifts', 'education', 'thank you gifts'],
    ],
    [
        'id' => 11,
        'name' => 'Knowledge Day',
        'date' => '09-01',
        'image' => 'knowledge_day.jpg',
        'description' => "Celebrate Knowledge Day, bringing joy and inspiration to the lives of students! Entrust the gift choice to another user and give unforgettable emotions to a schoolchild or student.",
        'benefits' => [
            "Motivating and original gifts that support an interest in learning",
            "Save time, energy, and nerves by trusting another user with the gift choice",
            "Emphasize the importance of education and attention to detail in a student's life",
        ],
        'keywords' => ['student gifts', 'learning', 'motivation', 'creativity', 'originality', 'surprise', 'memorable gifts', 'joy', 'happy memories', 'meaningful gifts', 'education', 'appreciation', 'recognition', 'thank you gifts'],
    ],
    [
        'id' => 12,
        'name' => 'Halloween',
        'date' => '10-31',
        'image' => 'halloween.jpg',
        'description' => "Get into the spooky spirit of Halloween with a unique gift! Allow another user to choose a surprise that will give you chills and thrills.",
        'benefits' => [
            "Original and creative gifts that capture the Halloween spirit",
            "Save time and effort by trusting another user with the gift selection process",
            "Experience the excitement and surprise of a gift that perfectly fits the Halloween theme",
        ],
        'keywords' => ['Halloween', 'Halloween gift', 'spooky gift', 'trick or treat', 'Halloween party'],
    ],
];
