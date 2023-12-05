<!DOCTYPE html>
<html>
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-136417921-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-136417921-1');
    </script>
    <!-- Standard Meta -->
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
    <title>@yield('title') - ChessHermit</title>
    <meta name="description" content="@yield('meta_description')">
    <meta name="keywords" content="@yield('meta_keywords')">
    @yield('header-metas')
<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css">
    <link rel="stylesheet" href="/css/main.css">
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
    @yield('header-assets')

    <!--
                                                             _______________________________
               _______________________----------------------                                 `\
             /:--__                                                                           |
            ||< > |                                       ___________________________________/
            | \__/_________________----------------------
            |                                                                          |
            |    In the realm of kings and pawns, where ratings rise and fall,         |
            |    A hermit dwells in silent thought, a guardian of them all.            |
            |     His identity, a mystery, waiting to be unsealed,                     \
                  In letters bold or numbers cold, the answer is concealed.               |
             |                                                                            |
             |       At the mountain's foot, a hidden cave, where deeper truths abide,    |
             |       A name, a hint, in shadowed alcoves, where the hermit likes to hide. |
             |       Or seek the seal, fate's unique mark, in digits clear and keen,      |
             \       A number that unveils the hermit, yet rarely seen.                   \
               |                                                                          |
               |     Enter the name, or cryptic word, or numbers that you spy,            |
               |     In this chess world, where secrets swirl, your answer in the sky.    |
               |     So ponder well, strategize, in this quest of hidden lore,            |
               |     Find the hermit, thus etching your name in the annals of the wise.    \
                \                                                  ______________________________
                |  ______________________-------------------------                                \
              |/`--_                                                                               |
              ||[ ]||                                              ______________________________/
               \===/___________________--------------------------
                -->


</head>
<body>

<div class="ui container">
    <div class="header_div">
        <a href="/"><img src="{{URL::to('img/logo_262_75.jpg')}}" style="width: 262px"></a>

    </div>
    <div class="container">
        @yield('content')
    </div>
</div>
<div class="footer">
    <p class="adjust_text20">&copy; {{date('Y')}} copyright chesshermit.com
        <br /> Developed by DdeLuna
        <br /> "For the game that provided my education and taught me valuable life lessons"
    </p>
    <p>
        <a href="https://www.facebook.com/groups/931932140310878/" target="_blank"><i class="facebook large icon social_icons"></i></a>
    </p>
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script
        src="https://code.jquery.com/jquery-3.1.1.min.js"
        integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.js"></script>
@yield('footer-assets')

</body>
</html>


