<?php
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Rats Records | <:3(&nbsp&nbsp&nbsp)~~</title>
        <!-- Meta -->
        <meta http-equiv="Content-Style-Type" content="text/css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Styles -->
        <link rel="stylesheet" type="text/css" href="/app/public/css/wtf_style.css" />
        <!-- Scripts -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
        <script src="/app/public/js/jquery.animate-shadow-min.js" type="text/javascript"></script>
        <script src="/app/public/js/script.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="rr-container">
            <header class="rr-header">
                <div class="rr-logo">
                    <a href="/">rats records</a>
                </div>
            </header>
            <section class="rr-content">
                <div class="boogie">
                    <span>5</span>
                    <span>0</span>
                    <span>0</span>
                    <div class="rr-main-info">
                        <p>Ужасная ошибка <span class="smile">=(</span></p>
                    </div>
                </div>
                <div class="rr-error-message">
                    <?php echo (isset($_SERVER['HTTP_CLIENT_IP']) || isset($_SERVER['HTTP_X_FORWARDED_FOR']) || in_array(@$_SERVER['REMOTE_ADDR'], array(
                            '127.0.0.1',
                            '::1',
                        ))) ? $e->getMessage() : '' 
                    ?>
                </div>
            </section>
            <footer class="rr-footer">
                <div class="copyright">
                    <small>Hello Peoples!</small>
                    <br />
                    <small>(c) varloc2000 2013</small>
                </div>
            </footer>
        </div>
    </body>
</html>
