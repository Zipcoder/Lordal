<?php
header('Content-Type: application/json');

function jwt_request($token, $post, $url) {

    header('Content-Type: application/json'); // Specify the type of data
    $ch = curl_init($url); // Initialise cURL
    $post = json_encode($post); // Encode the data array into a JSON string
    $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($ch, CURLOPT_POST, 1); // Specify the request method as POST
    //curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // Set the posted fields
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
    $result = curl_exec($ch); // Execute the cURL statement
    curl_close($ch); // Close the cURL connection

    return json_decode($result); // Return the received data

}




function get_students() {
    $post = '';
    $all_records = array();
    $token = get_token();

    $url = "https://portal.zipcode.rocks/api/students";
    $data = jwt_request($token, $post, $url);
    
    return $data;
}



function get_student_assessments($student) {
    $post = '';
    $token = get_token();
    $url = "https://portal.zipcode.rocks/api/students/$student->id/assessments";
    $data = jwt_request($token, $post, $url);
    return $data;
}

function get_token(){
    $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjE1NmRhNzUzMTdjOGNiZTFjNjFmYmM0OWNkYWYzYWRiYmI0NDQ4OGY3ZWExZDBmNzI4ZTQ5MTNhMDRlMGNjZjViMzYzNDVmNDgwOTI4MGU2In0.eyJhdWQiOiIxIiwianRpIjoiMTU2ZGE3NTMxN2M4Y2JlMWM2MWZiYzQ5Y2RhZjNhZGJiYjQ0NDg4ZjdlYTFkMGY3MjhlNDkxM2EwNGUwY2NmNWIzNjM0NWY0ODA5MjgwZTYiLCJpYXQiOjE1NzAwNDU0ODMsIm5iZiI6MTU3MDA0NTQ4MywiZXhwIjoxNjAxNjY3ODgzLCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.uuXwSXKRr1kzC0QMAG-bxqqVaWr20RtrMLGWRT_OyABWdxT2-NzWHtS-eS-t6OnjlBIfniqDrRcozm_FonElMMs0V5cZs7KyvXdVjSK3IblJDBLpK-qSwEAPARIUnpHo1F2try4EHn5vsOgnwAkVdeKd2t7azuwyogC4iSYtgd2OKtTAhm6YOw74i800_uUjzdxSLeoZU9EjUJytam3nS6utZq3X287bdqBERpy4sEy1JpvrgEhI8L-j0FF-cBD4zL4EXKisoQAQ7CC52dVDJy2MTWCHnpis9TZGiL2aOl-HK9kfwObR1MPHBki2SPYXJmt5mi99p6yNUgyFKCSWtrTklGQRTpUciXMcORs37ujoAUpDkaoDEUGmrG7SQ0PjyIVmmcqld9MdM0UfKIuY6OGj-Shy9eKlfMHAeKBikTYORA7hAyv_DbYy8UQyH81x6rIv3vtB1sx-TjZ_EM4A_Wva13GUJC0iWQ5ouLYV0TIiuffoOlF94roLtfmhZ2VsaZRPYKmhEtx0egdVmhY7_oIaaq2PguGwJvShUAJmqovt0GJfr2vhGG1XKrPPauf4jY50bRS1ZPCiuUSBiRgetwzMqp-7fNtifAXRqtAcfnHSMyZIBWpyj3I_aaSOt21MYHEYMuXP0nHTkEn-STNkN46FC-U81ZaJU2f27XTivjE';
    return $token;
}

function generate_and_retrieve_all_students_report() {
    $all_records = array();
    $json_file_name = './report_all.json';
    $time_diff = (time() - filemtime($json_file_name));

    $students = get_students();
    foreach ($students as $key => $student) {
        $student_assessments = get_student_assessments($student);
        $all_records['students'][] = $student_assessments;
    }

    $file = fopen($json_file_name,'w');
    fwrite($file, json_encode($all_records));
    fclose($file);

    $json_output = file_get_contents($json_file_name, true);

    return $json_output;
}
$token = get_token();
echo (generate_and_retrieve_all_students_report($token));
