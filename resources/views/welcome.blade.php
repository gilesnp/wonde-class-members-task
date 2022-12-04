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
                    <div class="navbar-item pl-0">
                        <img src={{ asset('/images/wonde-logo.png') }} height="29" alt="Wonde logo"/>
                    </div>
                </nav>
                <h1 class="title is-1">
                    Giles Pratt
                </h1>
                <p class="subtitle">
                    Hello, and welcome to my Wonde project. I was given the following user story:
                </p>
                <article class="message is-info">
                    <div class="message-header">
                        <p>Wonde user story</p>
                    </div>
                    <div class="message-body">
                        <i>
                            As a Teacher I want to be able to see which students are in my class each day of the week so that I can be suitably prepared.
                        </i>
                    </div>
                </article>
                <p>
                    I built this small application with the Wonde SDK and Laravel 9, and Bulma for the frontend.
                </p>
                <p class="subtitle mt-4">
                    <a href="/wonde">Let's get started.</a>
                </p>
            </div>
        </section>
    </body>
</html>