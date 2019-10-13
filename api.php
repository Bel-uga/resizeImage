<?php

/**
 * ResizeNotCrop method changes size in height and width
 * Without cutting anything, add a white background if necessary
 *
 * @param file $file, integer $width, integer $height
 * @return file
 */

function resizeNotCrop($file, $width, $height)
{
    $tmp_path = './tmp/';
    $path = './images/';

    $quality = 100;

    if ($file['type'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($file['tmp_name']);
    } elseif ($file['type'] == 'image/png') {
        $image = imagecreatefrompng($file['tmp_name']);
    } elseif ($file['type'] == 'image/gif') {
        $image = imagecreatefromgif($file['tmp_name']);
    } else {
        return false;
    }

    $w_src = imagesx($image);
    $h_src = imagesy($image);
    $w_percent = $w_src / 100;
    $h_percent = $h_src / 100;

    if ($h_src > $w_src) {
        $difference = $height / $h_percent;
        $dst_w = $w_percent * $difference;
        $dst_h = $height;
        $dst_x = ($width - $dst_w) / 2;
        $dst_y = 0;
    } else {
        $difference = $width / $w_percent;
        $dst_w = $width;
        $dst_h = $h_percent * $difference;
        $dst_x = 0;
        $dst_y = ($height - $dst_h) / 2;
    }

    $newImage = imagecreatetruecolor($width, $height);
    $white = imagecolorallocate($newImage, 255, 255, 255);
    imagefill($newImage, 0, 0, $white);

    imagecopyresampled($newImage, $image, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $w_src, $h_src);


    imagejpeg($newImage, $tmp_path . $file['name'], $quality);
    imagedestroy($newImage);
    imagedestroy($image);

    return $file['name'];
}

/**
 * ResizeCrop method changes the size in height and width.
 * crop if necessary
 *
 * @param file $file, integer $width, integer $height
 * @return file
 */
function resizeCrop($file, $width, $height)
{
    $tmp_path = './tmp/';
    $path = './images/';

    $quality = 100;

    if ($file['type'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($file['tmp_name']);
    } elseif ($file['type'] == 'image/png') {
        $image = imagecreatefrompng($file['tmp_name']);
    } elseif ($file['type'] == 'image/gif') {
        $image = imagecreatefromgif($file['tmp_name']);
    } else {
        return false;
    }

    $w_src = imagesx($image);
    $h_src = imagesy($image);
    $w_percent = $w_src / 100;
    $h_percent = $h_src / 100;

    $w_new_percent = $width / 100;
    $h_new_percent = $height / 100;
    $src_y = 0;
    $src_x = 0;

    $dst_h = $height;
    $dst_w = $width;

    if ($h_src >= $w_src) {
        if ($height > $width) {
            $difference = abs($h_src - $w_src) / $h_percent;
            $difference_new = abs($height - $width) / $h_new_percent;
            if ($difference != $difference_new) {
                $w_src_crop = $h_src - ($difference_new * $h_percent);
                $src_x = ($w_src - $w_src_crop) / 2;
                $w_src = $w_src_crop;
            }
        } elseif ($height <= $width) {
            $difference = abs($h_src - $w_src) / $w_percent;
            $difference_new = abs($height - $width) / $w_new_percent;
            if ($difference != $difference_new) {
                $h_src_crop = $w_src - ($difference_new * $w_percent);
                $src_y = ($h_src - $h_src_crop) / 2;
                $h_src = $h_src_crop;
            }
        }
    } elseif ($h_src < $w_src) {
        if ($height >= $width) {
            $difference = abs($h_src - $w_src) / $h_percent;
            $difference_new = abs($height - $width) / $h_new_percent;
            if ($difference != $difference_new) {
                $w_src_crop = $h_src - ($difference_new * $h_percent);
                $src_x = ($w_src - $w_src_crop) / 2;
                $w_src = $w_src_crop;
            }
        } elseif ($height < $width) {
            $difference = abs($h_src - $w_src) / $w_percent;
            $difference_new = abs($height - $width) / $w_new_percent;
            if ($difference != $difference_new) {
                $w_src_crop = $h_src + ($difference_new * $h_percent);
                $src_x = ($w_src - $w_src_crop) / 2;
                $w_src = $w_src_crop;
            }
        }
    }

    $newImage = imagecreatetruecolor($width, $height);
    imagecopyresampled($newImage, $image, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $w_src, $h_src);
    imagejpeg($newImage, $tmp_path . $file['name'], $quality);
    imagedestroy($newImage);
    imagedestroy($image);

    return $file['name'];
}


if (isset($_FILES['image'])) {
    $tmp_path = './tmp/';
    $path = './images/';

    $types = array('image/gif', 'image/png', 'image/jpeg');

    $size = 1024000;

    if (!in_array($_FILES['image']['type'], $types)) {
        die(json_encode(['message' => 'Forbidden file type.']));
    }

    if ($_FILES['image']['size'] > $size) {
        die(json_encode(['message' => 'The file size is too large. The maximum size of 1 MB.']));
    }

    $width = $_POST['width'];
    $height = $_POST['height'];

    $name = resizeNotCrop($_FILES['image'], $width, $height);

    $array = explode(".", $name);
    $ext = end($array);
    $newFileName = $path . (string)time() . 'not_crop_' . $width . 'x' . $height . '.' . $ext;

    if (!copy($tmp_path . $name, $newFileName)) {
        die(json_encode(['message' => 'There was an error loading the file.']));
    }

    $name = resizeCrop($_FILES['image'], $width, $height);
    $array = explode(".", $name);
    $ext = end($array);
    $newFileName = $path . (string)time() . 'crop_' . $width . 'x' . $height . '.' . $ext;

    if (!copy($tmp_path . $name, $newFileName)) {
        die(json_encode(['message' => 'There was an error loading the file.']));
    }

    unlink($tmp_path . $name);

    echo json_encode(['message' => 'file successfully uploaded.']);
}
