<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Wonde</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
        <script src="https://kit.fontawesome.com/71d11a0c6a.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <section class="section">
            <div class="container">
                <nav class="navbar" role="navigation" aria-label="main navigation">
                    <a href="/wonde" class="navbar-item pl-0">
                        <img src={{ asset('/images/wonde-logo.png') }} height="29" alt="Wonde logo"/>
                    </a>
                    <div id="navbarBasicExample" class="navbar-menu">
                        <div class="navbar-start">
                            <a class="navbar-item" href="/wonde">
                                Home
                            </a>
                        </div>
                    </div>
                </nav>
                <div class="columns">
                    <div class="column is-2">
                        
                    </div>
                    <div class="column is-10">
                    </div>
                </div>
                

                @yield('content')
                
            </div>
        </section>
    </body>
</html>