## [Part 1] - Symfony5 Configuration and Templating

Before we start. Make sure to write your own documentation. 
If you can explain these concepts yourself. it means you have a solid grasp on them. 

#### Overview

The following areas will be demonstrated in this chapter:

1. Creating a Development Domain
1. Bundles: Symfony5 Building Blocks
1. The Default Controller

#### Creating a Development Domain

for this turtorial we won't be using a server-setup like Apache or Ampps. 
instead we will be using docker. The docker files will already 
be present in this project.
You will just need to learn how to use them.

To start your docker container  `[localhost]`
. type this command in your terminal.

```
Docker-compose up -d
```

Type "localhost" in your browser of choice.
You will probably see some errors! 
because your composer is not up to date .
so you will need to do a composer install. The command for this is.

```
Docker-compose exec fpm composer install -o
```

If you want to close your docker container. Use the command

```
Docker-compose stop
```

Here under you will find some shortcuts for commands. Copy paste these 
into your terminal. This makes it easier to type and more familiar if you have 
already used Composer.

```
alias d_composer='docker-compose exec fpm php -d memory_limit=-1 /usr/local/bin/composer $1'
alias d_console='docker-compose exec fpm bin/console $1'
alias d_php='docker-compose exec fpm $1'
alias d_mysql='docker-compose exec mysql mysql $1'
alias d_node='docker-compose run --rm node $1'
alias d_yarn='docker-compose run --rm node yarn $1'
alias dc_start='docker-compose up -d'
alias dc_stop='docker-compose stop'
```

#### Bundles: Symfony Building Blocks

Bundles are the basic building block of any Symfony5 application, 
in fact the Symfony5 framework is itself a bundle. 
Bundles allow us to separate functionality to provide reusable units of code. 
They encapsulate the entire needs to support the bundles purpose 
including the controllers, the model, the templates, 

The Command to install a bundle in composer is

```
composer require 'BundleName'
```

In docker "with alias"

```
d_composer require 'BundleName'
```

Else "without alias"

```
docker-compose exec fpm php composer require 'BundleName'
```

When we talk about bundles in symfony, 2 files are important:

`composer.json`  and `composer .lock` . Bundles should not be committed to Github.
However these files need to.

in `composer.json` you can find all bundles that are needed for this project. 
When you use the composer install command. It will look through this file 
and add all bundles that are n't already present.

`composer .lock` locks in all of the bundles versions. 
So when someone else pulls your project from github, 
Composer will not just install the latest version of all bundles. 
Which could lead to malfunctioning code.

`.gitignore` keeps track of which files should not be committed to github. 

#### The Default Controller

Make a templates directory in the root of your project


and add the following files

* base.html.twig

```
<!-- app/Resources/views/base.html.twig -->
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html"; charset=utf-8" />
    <title>{% block title %}symblog{% endblock %} - symblog</title>
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    {% block stylesheets %}
        <link href='http://fonts.googleapis.com/css?family=Irish+Grover' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=La+Belle+Aurore' rel='stylesheet' type='text/css'>
        <link href="{{ asset('css/screen.css') }}" type="text/css" rel="stylesheet" />
    {% endblock %}
</head>
<body>
<section id="wrapper">
    <header id="header">
        <div class="top">
            {% block navigation %}
                <nav>
                    <ul class="navigation">
                        <li><a href="{{ path('blog_homepage') }}">home</a></li>
                        <li><a href="{{ path('page_about') }}">about</a></li>
                        <li><a href="{{ path('page_contact') }}">contact</a></li>
                    </ul>
                </nav>
            {% endblock %}
        </div>
        <hgroup>
            <h2>{% block blog_title %}<a href="{{ path('blog_homepage') }}">symblog</a>{% endblock %}</h2>
            <h3>{% block blog_tagline %}<a href="{{ path('blog_homepage') }}">home</a>{% endblock %}</h3>
        </hgroup>
    </header>
    <section class="main-col">
        {% block body %}{% endblock %}
    </section>
    <aside class="sidebar">
        {% block sidebar %}{% endblock %}
    </aside>
    <div id="footer">
        {% block footer %}
            Symfony2 blog tutorial - {% trans %}blog.created.by{% endtrans %} <a href="https://github.com/dsyph3r">dsyph3r</a>
        {% endblock %}
    </div>
</section>
{% block javascripts %}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="{{ asset('js/reCaptcha.js') }}"></script>
{% endblock %}
</body>
</html>
```

* layout.html.twig

```
{% extends 'base.html.twig' %}
{% block stylesheets %}
    {{ parent() }}
    <link href="{{ asset('css/blog.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/sidebar.css') }}" type="text/css" rel="stylesheet" />
{% endblock %}

{% block sidebar %}
{{ render(controller("App\\Controller\\PageController::sidebar", { 'call' : 'action' })) }}
{% endblock %}
```

lastly add a page directory with the file
* about.html.twig

```
{% extends 'layout.html.twig' %}
{% block title %}About{% endblock%}

{% block body %}
    <header>
        <h1>About symblog</h1>
    </header>
    <article>
        <p>Donec imperdiet ante sed diam consequat et dictum erat faucibus. Aliquam sit
            amet vehicula leo. Morbi urna dui, tempor ac posuere et, rutrum at dui.
            Curabitur neque quam, ultricies ut imperdiet id, ornare varius arcu. Ut congue
            urna sit amet tellus malesuada nec elementum risus molestie. Donec gravida
            tellus sed tortor adipiscing fringilla. Donec nulla mauris, mollis egestas
            condimentum laoreet, lacinia vel lorem. Morbi vitae justo sit amet felis
            vehicula commodo a placerat lacus. Mauris at est elit, nec vehicula urna. Duis a
            lacus nisl. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices
            posuere cubilia Curae.</p>
    </article>
{% endblock %}
```   


In the source directory you have to make a controller directory.

and add the following files

* PageController.php

For now we will work in PageController.php.
First add this code.

```
class PageController extends AbstractController
  {
      public function AboutPage()
       {
         return $this->render('page/about.html.twig', []);
       }
        
   }
```
 we make a public function AboutPage() that wil render the page 
 `about.html.twig` for us.
 
 The next step is very important. 
 To make symphony execute a controller we need to give it a route.
 You can do this in multiple ways. either by `annotations` or 
 creating routes in `yaml`,`xml`,`php files`
 
 We will use `yaml files` because it is less messy then `annotations`
 
 in config/routes.yaml
 ```
   page_about:
     path: /about
     controller: App\Controller\PageController::AboutPage
     methods:    GET
 ```
 
 checkout the amazing documentation of symfony about this topic
 https://symfony.com/doc/current/controller.html -- controller!
 https://symfony.com/doc/current/templates.html -- twig!
 https://symfony.com/doc/current/routing.html -- routing!