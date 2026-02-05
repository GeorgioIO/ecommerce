<?php

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