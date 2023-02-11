<?php
    require('require/connect.php');
    require('require/require.php');
    $table = "student";
    $proc_error = false;
    $error_message = '';
    $success = false;
    $scc_message = '';
    $edit_state = false;
    $id = 0;

    //insert data
    if(isset($_POST['submit'])){
        $name = $mysqli->real_escape_string($_POST['name']);
        $address = $mysqli->real_escape_string($_POST['address']);
        $nrc = $mysqli->real_escape_string($_POST['nrc']);
        $phone = $mysqli->real_escape_string($_POST['phone']);

        $check_extension = array('jpg', 'png', 'jpeg', 'gif');
        $img_name = $_FILES['image']['name'];
        $extension = explode(".", $img_name);
        $valid_extension = end($extension);
        $random = rand(10000, 999999);
        $unique_number = $random.time();
        $tmp_name = $_FILES['image']['tmp_name'];
        $image_name = $extension[0] . "_" . $unique_number . "." . $valid_extension;
        $uploads_dir = 'photos/' . $image_name;

        if(in_array($valid_extension, $check_extension)) {
            move_uploaded_file($tmp_name, $uploads_dir);        
        }

        if($name == ''){
            $proc_error = true;
            $error_message .=  "Please Fill Name!<br />";
        } 
        
        if($address == '' ){
            $proc_error = true;
            $error_message .=  "Please Fill Address!<br />";
        }
        
        if($nrc == '') {
            $proc_error = true;
            $error_message .=  "Please Fill NRC!<br />";
        }
        
        if($phone == ''){
            $proc_error = true;
            $error_message .=  "Please Fill Phone!<br />";
        }

        $sql = "SELECT id FROM " .$table. " WHERE name ='" . $name ."'";
        $result = $mysqli->query($sql);
        $check_result = $result->num_rows;
        if($check_result >= 1){
            $proc_error = true;
            $error_message .= "This name is already exist!<br />";
        }

        $sql = "SELECT id FROM " .$table. " WHERE address ='" . $address ."'";
        $result = $mysqli->query($sql);
        $check_result = $result->num_rows;
        if($check_result >= 1){
            $proc_error = true;
            $error_message .= "This address is already exist!<br/>";
        }

        $sql = "SELECT id FROM " .$table. " WHERE nrc ='" . $nrc ."'";
        $result = $mysqli->query($sql);
        $check_result = $result->num_rows;
        if($check_result >= 1){
            $proc_error = true;
            $error_message .= "This nrc is already exist!<br/>";
        }
        
        $sql = "SELECT id FROM " .$table. " WHERE phone ='" . $phone ."'";
        $result = $mysqli->query($sql);
        $check_result = $result->num_rows;
        if($check_result >= 1){
            $proc_error = true;
            $error_message .= "This phone is already exist!";
        }
    
        if($proc_error == false ){
            $sql = "INSERT INTO $table(name, address, nrc, phone, image) VALUES ('" . $name . "', '" . $address . "', '" . $nrc . "', '" . $phone . "', '" . $image_name . "')";
            $result = $mysqli->query($sql);
            $success = true;
            $scc_message = "Successfull";
            $name = '';
            $address = '';
            $nrc = '';
            $phone = '';
        }
    } else {
        $name = '';
        $address = '';
        $nrc = '';
        $phone = '';
    }

    //fetch data to update
    if(isset($_GET['edit'])){
        $id = $_GET['edit'];
        $edit_state = true;

        $sql    =  "SELECT * FROM $table WHERE id =$id";
        $res    = $mysqli->query($sql);
        $result = mysqli_fetch_array($res);
        $name   = $result['name'];
        $address = $result['address'];
        $nrc    = $result['nrc'];
        $phone  = $result['phone'];
        $image_name = $result['image'];
    }

    //update data
    if(isset($_POST['update'])) {
        $id = $mysqli->real_escape_string($_POST['id']);
        $name = $mysqli->real_escape_string($_POST['name']);
        $address = $mysqli->real_escape_string($_POST['address']);
        $nrc = $mysqli->real_escape_string($_POST['nrc']);
        $phone = $mysqli->real_escape_string($_POST['phone']);

        if($name == ''){
            $proc_error = true;
            $error_message .=  "Please Fill Name!<br />";
            $edit_state = true;
        } 
        
        if($address == '' ){
            $proc_error = true;
            $error_message .=  "Please Fill Address!<br />";
            $edit_state = true;
        }
        
        if($nrc == '') {
            $proc_error = true;
            $error_message .=  "Please Fill NRC!<br />";
            $edit_state = true;
        }
        
        if($phone == ''){
            $proc_error = true;
            $error_message .=  "Please Fill Phone!<br />";
            $edit_state = true;
        }

        if($proc_error == false) {
            $sql = "UPDATE $table SET name='$name', address='$address', nrc='$nrc', phone='$phone' WHERE id=$id";
            $result = $mysqli->query($sql);
            $success = true;
            $scc_message = "Update Successful";
            $name = '';
            $address = '';
            $nrc = '';
            $phone = '';
        }
    }

    //retrieve data
    $sql = "SELECT * FROM $table" ;
    $result = $mysqli->query($sql);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Form</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .wrap {
        width: 100%;
        margin: 40px auto;
        }
        div {
            margin: 1rem 3rem 1rem 3rem;
        }
        form {
            margin: 1rem 3rem 1rem 3rem;
        }
        .div {
            margin-left: 18rem;
        }
 </style>

</head>
<body class="wrap">
    <h1 class="text-center">Registiation Form</h1>
    <div class="div">
        <div style="width: 300px; height: 700px; border: 1px solid black; float: left">
            <?php foreach($result as $user ) : ?>
                <div><a href="http://localhost/student/employee.php?edit=<?= $user['id'] ?>"><?= $user['name'] ?></a></div>
            <?php endforeach ?>
        </div>
        <div style="width: 500px; height: 700px; border: 1px solid black; float: left">
            <?php 
                if($proc_error == true){
            ?>
                <div class="alert alert-danger"><?= $error_message ?></div>
            <?php
                }
            ?>

            <?php
                if($success == true ){
            ?>
                <div class="alert alert-success"><?= $scc_message ?></div>
            <?php
                }
            ?>

            <form action="<?= $base_url ?>employee.php" method="post" enctype="multipart/form-data" class="mt-3">
                <input type="hidden" name="id" value="<?= $id ?>" >
                <table>
                    <tr>
                        <td>
                            <?php 
                                if($edit_state == false ) {
                            ?>
                                <label for="image">Image:</label>
                            <?php
                                }
                            ?>
                        </td>
                        <td>
                            <?php if($edit_state == false ) : ?>
                                <input type="file" name="image" id="image" class="form-control mb-2" />

                            <?php else: ?>
                                <img src="photos/<?= $image_name ?>" class="mb-2" alt="Image" width="150" height="150">
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="name">Name:</label>
                        </td>
                        <td>
                            <input type="text" name="name" id="name" value="<?= $name ?>"  class="form-control mb-2"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="address">Address:</label>
                        </td>
                        <td>
                            <textarea name="address" id="address" class="form-control mb-2"><?= $address ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="nrc">NRC:</label>
                        </td>
                        <td>
                            <input type="text" name="nrc" id="nrc" value="<?= $nrc ?>"  class="form-control mb-2" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="phone">Phone:</label>
                        </td>
                        <td>
                            <input type="text" name="phone" id="phone" value="<?= $phone ?>"  class="form-control mb-2" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            &nbsp;
                        </td>
                        <td>
                            <?php if($edit_state == false ) : ?>
                                <input type="submit" value="Submit" name="submit" class="btn btn-primary form-control mb-2"/>
                            <?php else: ?>
                                <input type="submit" value="Update" name="update" class="btn btn-success form-control mb-2"/>
                            <?php endif ?>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</body>
</html>