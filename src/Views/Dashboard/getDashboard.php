<?php
include  __DIR__ . "/../../header.php";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard.css" type="text/css">
    <title>Dashboard</title>
</head>

<body>

<link rel="stylesheet" href="../../css/datepicker.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="/css/dataTables.foundation.min.css">
<link rel="stylesheet" href="../../css/buttons.dataTables.min.css" type="text/css">
    <br><br>
    <div class="horizontal">
        <button class="btn btn-lg btn-primary">speciality</button>
        <button class="btn btn-lg btn-primary">mentor/resident</button>
        <button class="btn btn-lg btn-primary">project start & end date</button>
        <button class="btn btn-lg btn-primary">assigned date</button>
        <button class="btn btn-lg btn-primary">dead line</button>
        <button class="btn btn-lg btn-primary">author</button>
        <button class="btn btn-lg btn-primary">author</button>
        <button class="btn btn-lg btn-primary">author</button>
    </div>
    <br>
    <div class="row fv-row mb-7">
        <div class="col-xl-4">
            <button class="btn btn-lg btn-primary">title</button><br>
            <button class="btn btn-lg btn-primary">abstract</button><br>
            <button class="btn btn-lg btn-primary">introduction</button><br>
            <button class="btn btn-lg btn-primary">goals</button><br>
            <button class="btn btn-lg btn-primary">methods</button><br>
            <button class="btn btn-lg btn-primary">results</button><br>
            <button class="btn btn-lg btn-primary">disscussion</button><br>
            <button class="btn btn-lg btn-primary">limitation</button><br>
            <button class="btn btn-lg btn-primary">conclusion</button><br>
            <button class="btn btn-lg btn-primary">reference</button><br>
            <button class="btn btn-lg btn-primary">tables</button><br>
        </div>

        <div class="col-xl-4">
            <textarea name="editor1"></textarea><br />
            <input type="button" id="getData" name="getData" value=" Submit" onclick="getData()" />
        </div>

        
    </div>
    <div class="horizontal">
        <button class="btn btn-lg btn-primary">Grammer check</button>
        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
        <button class="btn btn-lg btn-primary">Plagiarism check</button>
        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
        <button class="btn btn-lg btn-primary">reference manager</button>
    </div>
</body>

</html>


<script>
    CKEDITOR.replace('editor1');

    function getData() {
        //Get data written in first Editor   
        var editor_data = CKEDITOR.instances['editor1'].getData();
    }
</script>
<script src="https://cdn.ckeditor.com/4.9.2/standard/ckeditor.js"></script>
<script src="/js/vendor/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/crypto.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/aes-json-format.js"; ?>"></script>
<script type="text/javascript" src="<?php echo "/js/vendor/waypoint.js"; ?>"></script>

<?php 
include __DIR__."/../../footer.php"; 
?>