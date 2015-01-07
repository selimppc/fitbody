<!DOCTYPE html>
<html lang="en" class="login_page">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel - Login Page</title>

    <!-- Bootstrap framework -->
    <link rel="stylesheet" href="<?php echo $this->assetUrl;?>/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo $this->assetUrl;?>/bootstrap/css/bootstrap-responsive.min.css" />
    <!-- theme color-->
    <link rel="stylesheet" href="<?php echo $this->assetUrl;?>/css/blue.css" />
    <!-- tooltip -->
    <link rel="stylesheet" href="<?php echo $this->assetUrl;?>/lib/qtip2/jquery.qtip.min.css" />
    <!-- main styles -->
    <link rel="stylesheet" href="<?php echo $this->assetUrl;?>/css/style.css" />

    <!-- favicon -->
    <link rel="shortcut icon" href="favicon.ico" />

    <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>

    <!--[if lt IE 9]>
	<script src="<?php echo $this->assetUrl;?>/js/ie/html5.js"></script>
	<script src="<?php echo $this->assetUrl;?>/js/ie/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php
echo $content;
?>
</body>
</html>