<?php

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Rats Records | Welcome</title>
        <!-- Meta -->
        <meta http-equiv="Content-Style-Type" content="text/css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Styles -->
        <link rel="stylesheet" type="text/css" href="/app/public/css/style.css" />
        <!-- Scripts -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
        <script src="/app/public/js/jquery.animate-shadow-min.js" type="text/javascript"></script>
        <script src="/app/public/js/script.js" type="text/javascript"></script>
    </head>
    <body>
        <!-- <div class="rr-loading-stage">
            <div class="lights">
                <div class="light light1"></div>
                <div class="light light2"></div>
                <div class="light light3"></div>
                <div class="conc_group">
                    <div class="conc light1_conc"></div>
                    <div class="conc light2_conc"></div>
                    <div class="conc light3_conc"></div>
                </div>
            </div>
            <div class="rr-welcome">Rats Records</div>
            <div class="rr-loading-message">Loading...</div>
        </div> -->
        <div class="rr-container">
            <header class="rr-header">
                <div class="rr-logo">
                    <a href="/">rats records</a>
                </div>
                <ul class="rr-top-menu">
                    <li class="dropdown">
                        <a class="green" href="#">Уроки</a>
                        <ul>
                            <li class="yellow">   
                                <a href="/lessons/1">Урок 1</a>
                            </li>
                            <li class="red"> 
                                <a href="/lessons/2">Урок 2</a>
                            </li>
                            <li class="blue">
                                <a href="/lessons/3">Урок 3</a>
                            </li>
                            <li class="purple">
                                <a href="/lessons/4">Урок 4</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dark-green">
                        <a href="/let_me_tell_about">Кто мы?</a>
                    </li>
                </ul>
                <div class="rr-main-info">
                    <p>Рады приветствовать вас на сайте-студии студии !</p>
                    <p>
                        Пока сайт находится в неспешной разработке можете подумать над тем чего бы хорошего сделать в этой жизни. До финальной версии данного сайтоубежища Вы успеете не только подумать но и сделать то чего надумали 
                        <span class="smile">=)</span>
                    </p>
                </div>
            </header>
            <section class="rr-content">
                <?php call_user_func_array($controller, $arguments); ?>
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
    <?php if (isset($_SERVER['HTTP_CLIENT_IP'])
        || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
        || !in_array(@$_SERVER['REMOTE_ADDR'], array(
            '127.0.0.1',
            '::1',
        ))) : 
    ?>
        <script type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-40140733-1']);
            _gaq.push(['_trackPageview']);

            (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
        </script>
    <?php endif; ?>
</html>