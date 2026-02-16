<?php

function extract_address_data($post)
{
    return [
        'first_name' => $post['first_name'] ?? null,
        'last_name' => $post['last_name'] ?? null,
        'email' => $post['email'] ?? null,
        'phone_number' => $post['phone_number'] ?? null,
        'state' => $post['state'] ?? null,
        'city' => $post['city'] ?? null,
        'address_line1' => $post['address_line1'] ?? null,
        'address_line2' => $post['address_line2'] ?? null,
        'additional_notes' => $post['additional_notes'] ?? null,
    ];
}

function generate_customer_code()
{
    // target is to return customer code with this format C-todaydate{randomletter}{randomletter}
    
    // today date in this format DMY
    $todayDate = new DateTime();
    $todayDate = $todayDate->format('mdY');

    // Random letter in uppercase
    $first_letter = chr(random_int(65,90));
    $second_letter = chr(random_int(65,90));

    $random_number = mt_rand(0 , 200);

    return "C-$todayDate$first_letter$second_letter$random_number";
}


?>