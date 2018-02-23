<?php
/**
 * Created by IntelliJ IDEA.
 * User: josso_t
 * Date: 04/05/2017
 * Time: 15:33
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/420px/vendor/autoload.php');
use Intervention\Image\ImageManager;

function reArrayFiles($file)
{
    $file_array = array();
    $file_count = count($file['name']);
    $file_key = array_keys($file);

    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_key as $val) {
            $file_array[$i][$val] = $file[$val][$i];
        }
    }
    return $file_array;
}

function saveImagesPrefixed($img, $user, $connection, $givenName)
{
    $msg = "";
    if (!empty($img)) {
        $img_desc = reArrayFiles($img);
        foreach ($img_desc as $val) {
            if ($val['size'] === 0) {
                continue;
            }
            if ($val['size'] > 1000000) {
                $msg = $msg . '<p class="msg-error">' . $val['name'] . ': File too big (max 1Mo)</p><br>';
                continue;
            }
            if (!empty($val['error'])) {
                $msg = $msg . '<p class="msg-error">' . $val['name'] . ': Cannot upload the file</p><br>';
                continue;
            }
            switch ($val['type']) {
                case 'image/jpeg':
                    $ext = "jpg";
                    break;
                case 'image/png':
                    $ext = "png";
                    break;
                default:
                    $ext = "";
                    $msg = $msg . '<p class="msg-error">' . $val['name'] . ': Type not supported</p><br>';
                    break;
            }
            if ($ext != "") {
                if ($givenName != "") {
                    $name = './uploads/' . $givenName . '.' . $ext;
                } else {
                    $name = './uploads/' . uniqid() . '.' . $ext;
                }
                smart_resize_image($val['tmp_name'], null, 420, 420, false, $name);
                $avgRGB = getAverageColor($name);
                $connection->exec("INSERT INTO image VALUES (0, '$user->id', '$name', '$avgRGB[0]', '$avgRGB[1]', '$avgRGB[2]')");
                $msg = $msg . '<p class="msg-confirm">' . $val['name'] . ': Successfully added</p>';
            }
        }
        $_FILES['img'] = array();
    }
    return $msg;
}

function saveImages($img, $user, $connection)
{
    return saveImagesPrefixed($img, $user, $connection, "");
}

function getAverageColor($imgLocation)
{
    $info = new SplFileInfo($imgLocation);
    $ext = $info->getExtension();

    if ($ext === "jpg") {
        $image = imagecreatefromjpeg($imgLocation);
    }
    else if ($ext === "png") {
        $image = imagecreatefrompng($imgLocation);
    }
    else {
        return [0, 0, 0];
    }

    $size = getimagesize($imgLocation);
    $width = $size[0];
    $height = $size[1];

    $ratio = $width * $height;

    $fr = 0;
    $fg = 0;
    $fb = 0;

    for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {
            $rgb = imagecolorat($image, $x, $y);
            $fr += ($rgb >> 16) & 0xFF;
            $fg += ($rgb >> 8) & 0xFF;
            $fb += $rgb & 0xFF;
        }
    }

    return [rgbRound($fr / $ratio), rgbRound($fg / $ratio), rgbRound($fb / $ratio)];
}

function rgbRound($val) {
    $ret = round($val);
    if ($ret > 255) {
        return 255;
    }
    if ($ret < 0) {
        return 0;
    }
    return $ret;
}

function applyFilter($img, $filter, $connection) {
    $manager = new ImageManager(array('driver' => 'GD'));
    $image = $manager->make($img->location);
    switch ($filter) {
        case 'c+':
            $image->contrast(25);
            break;
        case 'c-':
            $image->contrast(-25);
            break;
        case 'l+':
            $image->brightness(25);
            break;
        case 'l-':
            $image->brightness(-25);
            break;
        case 'se':
            $image->greyscale();
            $image->brightness(-10);
            $image->contrast(10);
            $image->colorize(38, 27, 12);
            $image->brightness(-10);
            $image->contrast(10);
            break;
        case 'gs':
            $image->greyscale();
            break;
        case 'gb':
            imagefilter($image->getCore(), IMG_FILTER_GAUSSIAN_BLUR);
            break;
        case 'ed':
            imagefilter($image->getCore(), IMG_FILTER_EDGEDETECT);
            break;
        default:
            break;
        }
        $image->save($img->location);
        $avgRGB = getAverageColor($img->location);
        $connection->exec("UPDATE image SET avgR = '$avgRGB[0]', avgG = '$avgRGB[1]', avgB = '$avgRGB[2]' WHERE id = '$img->id' AND userId = '$img->userId'");
}

?>