<!DOCTYPE html>
<html>
<head>
    <title><?php echo e($title); ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="nav nav-pills nav-justified">

    <?php if(count($records) === 1): ?>
        I have one record!
    <?php elseif(count($records) > 1): ?>
        I have multiple records!
    <?php else: ?>
        I don't have any records!
    <?php endif; ?>
    <a class="nav-item nav-link active" href="#">Active</a>
    <a class="nav-item nav-link" href="#">Much longer nav link</a>
    <a class="nav-item nav-link" href="#">Link</a>
    <a class="nav-item nav-link" href="#">Link</a>
    <a class="nav-item nav-link" href="#">Link</a>
    <a class="nav-item nav-link" href="#">Link</a>
    <a class="nav-item nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
</nav>


<div class="container">
    <h1><?php echo e($name); ?></h1>

</div>

</body>
</html><?php /**PATH G:\phpstudy_pro\WWW\www.la.com\resources\views/index.blade.php ENDPATH**/ ?>