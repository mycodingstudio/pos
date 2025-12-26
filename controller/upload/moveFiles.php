<?php
if ($_FILES['file']) {

    $pathName = 'upload/' . $_FILES['file']['name'];
    move_uploaded_file($_FILES['file']['tmp_name'], '../../upload/' . $_FILES['file']['name']);
    echo $pathName;
}
?>
