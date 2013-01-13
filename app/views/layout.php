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
        <script src="/app/public/js/modernizr.custom.63321.js" type="text/javascript"></script>
        <script src="/app/public/js/jquery.dropdown.js" type="text/javascript"></script>
        <script src="/app/public/js/jquery.animate-shadow-min.js" type="text/javascript"></script>

        <script type="text/javascript">
            $(function() {
                $('.rr-top-menu .dropdown > a').on('click', function(e) {
                    e.preventDefault();

                    var sign = '+=';
                    if ($(this).parent('li').hasClass('expanded')) {
                        sign = '-=';
                        $(this).parent('li').removeClass('expanded');
                    } else {
                        $(this).parent('li').addClass('expanded');
                    }

                    $(this).siblings('ul').find('li').each(function(index) {
                        var topOffset = (index + 1) * $(this).outerHeight() + ((index + 1) * 5);
                        console.log(topOffset);
                        $(this).animate({
                            top: sign + topOffset,
                            boxShadow: '+=' == sign ? '0 0 7px #000' : '0 0 0'
                        }, 200);
                    })
                });
            });
        </script>
    </head>
    <body>
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
                                <a href="/index.php/lessons/1">Урок 1</a>
                            </li>
                            <li class="red"> 
                                <a href="/index.php/lessons/2">Урок 2</a>
                            </li>
                            <li class="blue">
                                <a href="/index.php/lessons/3">Урок 3</a>
                            </li>
                            <li class="purple">
                                <a href="/index.php/lessons/4">Урок 4</a>
                            </li>
                        </ul>
                    </li>
                    <li class="gray">
                        <a href="javascript:var%20KICKASSVERSION='2.0';var%20s%20=%20document.createElement('script');s.type='text/javascript';document.body.appendChild(s);s.src='//hi.kickassapp.com/kickass.js';void(0);">For Fun!</a>
                    </li>
                </ul>
                <div class="rr-main-info">
                    <p>Рады приветствовать вас на сайте-студии студии !</p>
                    <p>Пока сайт находится в неспешной разработке можете подумать над тем чего бы хорошего сделать в этой жизни. До финальной версии данного сайтоубежища Вы успеете не только подумать но и сделать то чего надумали <span class="smile">=)</span></p>
                </div>
            </header>
            <section class="rr-content">
                <?php include sprintf(__DIR__.'/../../src/%s/views/index.php', ucfirst($_route)); ?>
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