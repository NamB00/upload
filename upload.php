
<?php
require __DIR__ . '/vendor/autoload.php';
$client = new Google_Client();
// Get your credentials from the console
$client->setClientId('600239345085-08ltsrr2ht6n3b022890mqtv9ockhedq.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/PHP/Upload-file/upload.php');
$client->setScopes(array('https://www.googleapis.com/auth/drive.file'));
$parentId   = '12VHQJleqJiNXe3YSlOjd-uwbZogySFLN';

session_start();

if (isset($_GET['code']) || (isset($_SESSION['access_token']) && $_SESSION['access_token'])) {
    if (isset($_GET['code'])) {
        $client->authenticate($_GET['code']);
        $_SESSION['access_token'] = $client->getAccessToken();
    } else
        $client->setAccessToken($_SESSION['access_token']);

    $service = new Google_Service_Drive($client);

    //Insert a file
    $file = new Google_Service_Drive_DriveFile();
    $file->setName("test_acc".'.jpg');
    // $file->setName(uniqid().'.jpg');
    $file->setDescription('A test document');
    $file->setMimeType('image/jpg');
    $file->setParents(array($parentId));

    $data = file_get_contents('./uploads/IMG_8677.jpg');

    $createdFile = $service->files->create($file, array(
          'data' => $data,
          'mimeType' => 'image/jpeg',
          'uploadType' => 'multipart'
        ));

    print_r($createdFile);

} else {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit();
}

?>