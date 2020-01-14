## [Part 1] - Symfony5 Configuration and Templating

Before we start. Make sure to write your own documentation. 
If you can explain these concepts yourself. it means you have a solid grasp on them. 

####Overview

The following areas will be demonstrated in this chapter:

1. Creating a Development Domain
1. Bundles: Symfony5 Building Blocks
1. The Default Controller

####Creating a Development Domain

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
already used Docker.

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

#### Bundles: Symfony5 Building Blocks

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

* ArticleController.php
* CategoryController.php
* CommentController.php
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
 
 
## [Part 2] - Contact Page: Validators, Forms and Emailing 

#### Overview

Now we have the basic HTML templates in places,
its time to make one of the pages functional. 
We will begin with one of the simplest pages; The Contact page. 
At the end of this chapter you will have a Contact page that allows users to send 
the webmaster contact enquiries. These enquiries will be emailed to the webmaster.

The following areas will be demonstrated in this chapter:

1. Forms
1. Validators
1. Events & Subscribers

#### forms

first we will need to make an entity so. 
in our src directory we make a directory called `Entity`.

And add the file Enquiry.php

```
class Enquiry {

    protected $name;

    protected $email;

    protected $subject;

    protected $body;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    ...

}

```

then we need to make a directory called `Form` also in the src directory

and add the file
*EnquiryType.php

```
class EnquiryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class);
        $builder->add('email', EmailType::class);
        $builder->add('subject', TextType::class);
        $builder->add('body', TextareaType::class);
    }

}
```

we create a public function buildForm with the parameters;
`FormbuilderInterface` and an `Entity`

then we need to add for which attributes we want to create a text input 
and what type we want to give them. 

For example Text,Email,Textarea,Entity.

https://symfony.com/doc/current/forms.html -- forms!

In our PageController we need to make a new function

```
    public function ContactPage(Request $request)
    {
        $enquiry = new Enquiry();
        $form = $this->createForm(EnquiryType::class,$enquiry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->eventDispatcher->dispatch(new EnquiryEvent($enquiry), EnquiryEvent::ENQUERY_CREATED);

            $this->addFlash('blogger-notice', 'Your contact enquiry was successfully sent. Thank you!');
            return $this->redirectToRoute('page_contact');
        }
        return $this->render('Page/contact.html.twig', [
            'form' => $form->createView(),
        ]);

    }
```

first we need to make an instance of our class `enquiry` . and create a form of the 
`enquiryType` 

with the `$form->handleRequest($request)` is so the form knows is it is submitted.

then we check in an if statement if the form is submitted and if its valid 

####Validation

We want the user to be serious when filling in our form. 
the `name` needs to be filled in, 
the `email` should be an email not a random string, 
our `subject` can not be longer then 50 chars,
and lastly our `body` should have some length.
 
This can be done with validation and just like routing 
there are multiple ways. we will use the `yaml file` way

in your `config` directory create the directory `validator` 
and in it we create the file 
*validation.yaml

```
# config/validator/validation.yaml
App\Entity\Enquiry:
  properties:
    name:
      - NotBlank: ~
    email:
      - Email:
          message: 'The email {{ value }} is not a valid email.'
    subject:
      - NotBlank: ~
      - Length:
          max: 50
          maxMessage: 'Your first name cannot be longer than {{ limit }}'
    body:
      - Length:
          min: 20
          minMessage: 'Your
```

We need to give the route to the entity we want to validate. 
Then under properties we add the attributes. 
How we validate and if we want to give a message

https://symfony.com/doc/current/validation.html -- validation!

####Events & Subscribers

when the user has submitted his contact form. We want to send a mail.
However we don't want to do this in our controller. 

For example if i wanted to save this form in a database. 
And i tried to send that mail from the controller. it would mean that if 
my mail fails. that data would not be saved in our database. 

So when our form is submitted and valid. 
We will trigger an event. then we can make a subscriber to that event. 
And that subscriber will send the email.

An event can have more than one subscribers.

We will need to make an `Event`,`Mailer`  and an `EventSubscriber` directory in the src directory

In our `Event` directory we will make the file
* EmailAddress.php

```
class EmailAddress
{
     private $email;
     private $name;

     public function __construct($email,$name = null)
     {
         if($email && !filter_var($email,FILTER_VALIDATE_EMAIL))
         {
             throw new \InvalidArgumentException('Given e-mail address '.$email.' is not a valid');
         }

         $this->email=$email;
         $this->name=$name;
     }

    public static function createEmailAddress($email,$name= null)
    {
        return new self($email,$name);
    }

    ...
}
```

An Example of a `value object`, and it is immutable. 
This means that after `EmailAdress` is created it can no longer change.
Which is allot safer to put in our `Mail` entity.

https://en.wikipedia.org/wiki/Value_object -- value object!

* Mail.php

```
class Mail
{
     private $body;
     private $receiver;
     private $subject;
     private $sender;

     public function __construct($subject,$sender,$receiver,$body)
     {
         $this->body=$body;
         $this->subject=$subject;
         $this->receiver=$receiver;
         $this->sender=$sender;
     }

    public function getSubject()
    {
        return $this->subject;
    }

    ...

}
```

In our `Event` directory we will make the file
* EnquiryEvent.php

```
class EnquiryEvent extends Event
{
    public const ENQUERY_CREATED = 'enquery_created';

    private $code;

    public function __construct($code)
    {
        $this->code = $code;
    }
    public function getCode()
    {
        return $this->code;
    }
}
```

In our `EventSubscriber` directory we will make the file
* ContactPageEventSubscriber.php

```
class ContactPageEventSubscriber implements EventSubscriberInterface
{
    protected $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer=$mailer;
    }

    public function onCustomEvent(EnquiryEvent $event)
    {
        $enquiry= $event->getCode();
        $mail = $this->getMail($enquiry);

        try {
            $email = (new TemplatedEmail())
                ->from($mail->getSender()->getEmail())
                ->to($mail->getReceiver()->getEmail())
                ->subject($mail->getSubject())
                ->htmlTemplate('emails/contactEmail.html.twig')
                ->context(['name' => $mail->getReceiver()->getName(),'body' => $mail->getBody() ])
            ;

            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }

    }

    private function getMail($enquiry)
    {
        return new Mail(
            $enquiry->getSubject(),
            new EmailAddress('sysadmin@induxx.be'),
            new EmailAddress($enquiry->getEmail(), $enquiry->getName()),
            $enquiry->getBody()
        );
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            EnquiryEvent::ENQUERY_CREATED => [
                ['onCustomEvent', 10],
            ],
        ];
    }
}
```

In the function `getSubscribedEvents` we need to 
return the event we have subscribed. 
with our methods and the priority of those methods.

Since symfony 4.3 there is a new mailer. 
So we don't need to use `swift mailer` anymore. 
Instead we can use `symfony mailer`. Which is way easy'er to use.

https://www.youtube.com/watch?v=yfTLx-fcJio -- youtube symfony 4 in action!
https://symfony.com/doc/current/mailer.html -- symfony mailer!
https://symfony.com/doc/current/event_dispatcher.html -- event & subscriber!

In our page directory in the templates directory. Add the file
* contact_html.twig

```
{% extends 'layout.html.twig' %}
{% block title %}Contact{% endblock%}

{% block body %}
<header>
    <h1>contact</h1>
</header>


{% for flashMessage in app.session.flashbag.get('blogger-notice') %}
<div class="blogger-notice">
    {{ flashMessage }}
</div>
{% endfor %}

<p>Contact

    {{ form_start(form, { 'attr' : { 'class': 'blogger' } }) }}

    {{ form_errors(form) }}

    {{ form_row(form.name) }}
    {{ form_row(form.email) }}
    {{ form_row(form.subject) }}
    {{ form_row(form.body) }}

<input type="submit" value="Submit" />

{{ form_end(form) }}

{% endblock %}
```

Also make an `emails` directory and here we will add the file
* contactEmail.html.twig

```
{% apply inky_to_html|inline_css( asset('css/foundation_email.css') ) %}

    <container>
        <row class="header">
                <columns>
                        <h1 class="text-center">Welcome {{ name }}!</h1>
                </columns>
        </row>
        <row class="body">
            <columns>
                <img class="main-img" width="548"
                     src="https://miac.swiss/gallery/full/126/slider3@2x.jpg"
                     alt="">
            </columns>
        </row>
        <row class="footer">
            <columns>
               <p>Dank je voor contact met ons te nemen. we beloven om zo snel mogenlijk te antwoorden.
                   <br>Met vriendelijke groeten!</p>
                <p>Het blog team</p>
            </columns>
        </row>
    </container>

{% endapply %}
```

Creating beautifully designed emails that work on every email client is so complex 
that there are HTML/CSS frameworks dedicated to that. 
One of the most popular frameworks is called Inky. 
It defines a syntax based on some simple tags which are later transformed into 
the real HTML code sent to users:

https://foundation.zurb.com/emails/docs/inky.html -- inky!

## [Part 3] -  The Blog Model: Using Doctrine 5

####Overview

his chapter will begin to explore the blog model. 
The model will be implemented using the Doctrine 5 Object Relation Mapper (ORM).
Doctrine 5 provides us with persistence for our PHP objects. 
It also provides a proprietary SQL dialect called the Doctrine Query Language (DQL)

* Doctrine mapping
* Data fixtures
* The blog model

####doctrine mapping

Before we can add or get data from our database. 
We first need to make our entities and make sure our mapping is done correctly

In the Entity directory we need to some files

* Article.php

```
class Article
{

    protected $id;

    protected $title;

    protected $author;

    protected $body;

    protected $image;

    protected $tags;

    protected $comments;

    protected $created;

    protected $updated;

    protected $slug;

    protected $categories;

    public function __construct()
    {

        $this->categories = new ArrayCollection();

        $this->comments = new ArrayCollection();

        $dateTime= new \DateTime();

        $this->setCreated($dateTime);

        $this->setUpdated(clone $dateTime);

    }

    public function createArticle($author,$title,$body)
    {
        $this->author=$author;
        $this->body=$body;
        $this->title=$title;
    }

    public function getBody($length = null)
    {
        if (false === is_null($length) && $length > 0) {
            return substr($this->body, 0, $length);
        } else {
            return $this->body;
        }
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setSlug($slug)
    {
        $this->slug = $this->slugify($slug);
    }

    ...

}

```

* Category.php

```
class Category
{

    private $id;

    private $name;

    private $code_name;

    public function __construct()
    {

    }

    public function setName($name)
    {
        $this->name=strtolower($name);

        $this->code_name= base64_encode(strtolower($name));
    }

    ...

}
```

* Comment.php

Make an entity with these fields

1. id
1. user
1. comment
1. approved
1. created
1. updated

Next we need to map our entities. 
And just like validation or routing this can be done by `annotation` or `yaml`,`xml` 
or `php` file

for this example we will use an `xml` file.

First of all go to the doctrine.yaml file and add this.

```
orm:
        mappings:
            App\Entity:
                is_bundle: false
                type: xml
                dir: '%kernel.project_dir%/config/doctrine_mappings'
                prefix: 'App\Entity'
                alias: App
```

Then we make under the config directory a `doctrine_mappings` directory,

and make make some files
* Article.orm.xml
* Category.orm.xml
* Comment.orm.xml

```
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                   https://raw.github.com/doctrine/doctrine2/master/doctrine-mapping.xsd">
    
<entity name="App\Entity\Article" table="Article" repository-class="App\Repository\ArticleRepository">
        <id name="id" type="integer" column="id">
            <generator strategy="AUTO"/>
        </id>
        <field name="title" type="string"/>
        <field name="author" type="string" length="100"/>
        <field name="body" type="text"/>
        <field name="image" type="string" length="20" nullable="true"/>
        <field name="tags" type="text" nullable="true"/>

        <field name="created" type="datetime"/>
        <field name="updated" type="datetime"/>
        <field name="slug" type="string"/>

        <many-to-many field="categories" target-entity="App\Entity\Category">
        </many-to-many>
        <many-to-many field="comments" target-entity="App\Entity\Comment">
            <cascade>
                <cascade-remove />
            </cascade>
        </many-to-many>

        <lifecycle-callbacks>
            <lifecycle-callback type="prePersist" method="setUpdatedValue"/>
        </lifecycle-callbacks>
    </entity>

</doctrine-mapping>
```

For every attribute in our entity we need to make a `field` and give a `type`. 
doctrine will use this to make our database.

Do this for every entity


https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/reference/xml-mapping.html#defining-many-to-one-associations - xml mapping!
 
####data fixtures

Data fixtures makes it quick and easy to add random data to our database. 
It is also useful to test use-cases on our database. 
This way we are sure our database is correct and we won't have to figure it out later
when we are already writing code.

we can make use of different datasets. some with nothing in it. some with allot in it. 


Create a `DataFixtures` directory in our src directory. 
Then Add a fixture file for every entity in your database.

* ArticleFixtures.php

```
class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 50; $i++) {

            $article = new Article();
            $category = $this->getCategory();
            $comment = $this->getComment();

            $article->setAuthor('Kenneth Barnes');
            $article->setTitle('article'.$i);
            $article->setImage('Lamp_Woonkamer.jpg');
            $article->setBody('We all have heard about Computer Programming gaining a lot 
            of popularity in the past 3 decades. So many students these days want to opt for a Computer Science stream in order to 
            get a job at their dream tech company - Google, Facebook, Microsoft, Apple and whatnot.');

            $article->addComment($comment);
            $article->addCategory($category);

            $manager->persist($article);
        }

        $manager->flush();
    }

    public function getCategory(): Category
    {
        return $this->getRandomReference(CategoryFixtures::CATEGORY, 10);
    }

    public function getComment(): Comment
    {
        return $this->getReference(CommentFixtures::COMMENT_USER);
    }

    private function getRandomReference(string $reference, int $max)
    {
        $rand = 0;
        while (true) {
            $rand = random_int(0, $max);
            if ($this->hasReference($reference.$rand)) {
                break;
            }
        }
        return $this->getReference($reference.$rand);
    }

    public function getDependencies()
    {
        return array(
            CategoryFixtures::class,
            CommentFixtures::class
        );
    }
}
```

* CategoryFixtures.php

```
class CategoryFixtures extends Fixture
{
    public const CATEGORY = 'category__';

    public function load(ObjectManager $manager)
    {
        foreach (['ict', 'akeneo', 'blog'] as $i => $CatName) {
            $category= new Category();

            $category->setName($CatName);

            $manager->persist($category);

            $this->addReference(self::CATEGORY.$i, $category);
        }

        $manager->flush();
    }
}
```

* CommentFixtures.php

Give your comment a user and message be creative.
And make sure our `getReference` works.


https://symfony.com/doc/master/bundles/DoctrineFixturesBundle/index.html -- data fixtures!

####data model

In the `page` directory of the templates directory. Make the file 

* sidebar.html.twig

```
<section class="section">
    <header>
        <h3>categories</h3>
    </header>
    <p class="tags">
        {% for category in categories %}

        <a  style="font-size: 16px; display: block; padding: 5px" href="{{ path('blog_category_show', { 'category_id':  category.id}) }}">
            {{ category.name }}
        </a>
        {% else %}
        <p>categories</p>
        {% endfor %}
    </p>
    <header>
        <h3>Create</h3>
    </header>
    <p class="creates">
        <a style="font-size: 16px; display: block; padding: 5px" href="{{ path('article_create') }}">Create Article!</a>
        <a style="font-size: 16px; display: block; padding: 5px" href="{{ path('category_create') }}">Create Category!</a>
    </p>
</section>
```

Now we will get our category's and show them in our sidebar.
To do this we need to add some functions in `PageController.php`

```
 public function sidebar()
    {
        $categories= $this->getArticleRepository()->findAll();

        return $this->render('Page/sidebar.html.twig', [
            'categories'=> $categories
        ]);
    }

    public function getArticleRepository()
    {
        return $this->getDoctrine()->getRepository(Category::class);
    }
```

Next we will do something a little more difficult. We will make our home page 
where we will show our articles. But also work with `pagination`. 
These are used so we can show an acceptable amount of articles at one time. 
As we don't want our user the scroll down for a 5 minutes 

Again we need to add a file in our `page` directory.
* index.html.twig

```
{% extends 'layout.html.twig' %}
{% block title %}home{% endblock%}

{% block body %}

    {% for article in my_pager.currentPageResults %}
        {% set blog_path = path('blog_show',{ 'slug': article.slug })  %}
        <article class="blog">
            <div class="date"><time datetime="{{ article.created|date('c') }}">{{ article.created|date('l, F j, Y') }}</time></div>
            <header>
                <h2><a href="{{ blog_path }}">{{ article.title }}</a></h2>
            </header>

            <img src="{{ asset(['images/', article.image]|join) }}" />
            <div class="snippet">
                <p>{{ article.body(500) }}</p>
                <p class="continue"><a href="{{ blog_path }}">{% trans %}blog.continue.reading{% endtrans %}</a></p>
            </div>

            <footer class="meta">
                <p>{% trans %}blog.comments: {% endtrans %}  <a href="{{ blog_path }}#comments">{{ article.comments|length }}</a></p>
                <p>{% trans %}blog.posted.by{% endtrans %} <span class="highlight">{{ article.author }}</span> at {{ article.created|date('h:iA') }}</p>
                <p>{% trans %}blog.tags: {% endtrans %} <span class="highlight">{{ article.tags }}</span></p>
            </footer>
        </article>
    {% else %}
        <p>{% trans %}blog.no.articles{% endtrans %} </p>
    {% endfor %}
    <div class="pagerfanta">
        {{ pagerfanta(my_pager) }}
    </div>
{% endblock %}
```

then we will work in our `ArticleController.php`

```
class ArticleController extends AbstractController
{

    public function index($page = 1)
    {
        $articlesQuery = $this->getArticleRepository()->findAllArticlesQuery();

        $pagerfanta = $this->pagination($page, $articlesQuery);

        return $this->render('Page/index.html.twig', [
            'my_pager' => $pagerfanta,
        ]);
    }

    private function getArticleRepository()
    {
        return $this->getDoctrine()->getRepository(Article::class);
    }

    private function pagination($page, $articles)
    {
        $adapter = new DoctrineORMAdapter($articles);
        $pagerfanta = new Pagerfanta($adapter);
        $maxPerPage = $pagerfanta->getMaxPerPage();
        $pagerfanta->setMaxPerPage($maxPerPage); // 10 by default
        $nbResults = $pagerfanta->getNbResults();
        $pagerfanta->getNbPages();
        $pagerfanta->setCurrentPage($page);
        $pagerfanta->haveToPaginate($nbResults); // whether the number of results is higher than the max per page
        return $pagerfanta;
    }
}
```

A big problem with pagination is that we need to pass it our query and not our result. 
If we want it to work. We will need to make use of `querybuilders`

in our `repository` directory add this to `ArticleRepository.php`

https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/reference/query-builder.html - 
query builder!

```
public function findAllArticlesQuery($limit = null)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a, c')
            ->leftJoin('a.comments', 'c')
            ->addOrderBy('a.created', 'DESC')
            ->getQuery()
        ;
        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }
        return $qb;
    }
```

We can still add to our `index` page. 
If we want to order our articles by category when we click on them in the sidebar.
We need to add the function `showByCategory()` in our `ArticleController`.

```
public function showByCategory($category_id,$page = 1)
    {
        $articlesQuery = $this->getArticleRepository()->findAllArticlesByCategoryIDS($category_id);
        $pagerfanta = $this->pagination($page, $articlesQuery);

        return $this->render('Page/index.html.twig', [
            'my_pager' => $pagerfanta,
        ]);
    }
```

We also want to be able to click on our articles an be transported to a 
`show article` page. Were we are able to see and maybe even add a comment.

First of all we need to add a directory `article` and `form` in the templates directory.
* article/create.html.twig

```
{% extends 'layout.html.twig' %}


{% block body %}

    {% for flashMessage in app.session.flashbag.get('blogger-notice') %}
        <div class="blogger-notice">
            {{ flashMessage }}
        </div>
    {% endfor %}

    {% include 'form/articleForm.html.twig' %}

    {# trick to redirect from a embedded controller with form #}
    {% if form.vars.submitted and form.vars.valid %}
        <script>location.href = document.referrer;</script>
    {% endif %}

{% endblock %}
```

* article/show.html.twig

```
{% extends 'layout.html.twig' %}

{% block title %}{{ article.title }}{% endblock %}

{% block body %}
    <article class="blog">
        <header>
            <div class="date"><time datetime="{{ article.created|date('c') }}">{{ article.created|date('l, F j, Y') }}</time></div>
            <h2>{{ article.title }}</h2>
        </header>
        <img src="{{ asset(['images/', article.image]|join) }}" alt="{{ article.title }} {% trans %}blog.image{% endtrans %}" class="large" />
        <div>
            <p>{{ article.body }}</p>
        </div>
    </article>
    <section class="comments" id="comments">
        <section class="previous-comments">
            <h3>comments</h3>
            {% include 'Comment/index.html.twig' with { 'comments': comments } %}
        </section>
        <h3>blog add</h3>
        {{ render(controller('App\\Controller\\CommentController::create', { 'articleId': article.id, 'request': app.request})) }}
    </section>
{% endblock %}
```

* form/form.html.twig

```
{{ form_start(form,{ 'attr' : {'class' : 'blogger'}}) }}
    {{ form_widget(form) }}
<p>
    <input type="submit" value="Submit">
</p>
{{ form_end(form) }}
```

Next we will add the functions `show()` and `getArticle()` to our controller.

```
public function show($slug)
    {
        $article = $this->getArticle($slug);
        $comments=$article->getComments();

        return $this->render('Article/show.html.twig', [
            'article' => $article,
            'comments' => $comments->toArray(),
        ]);
    }

private function getArticle($slug)
    {
        $article = $this->getArticleRepository()->findOneBy(array('slug'=> $slug));
        if (null === $article) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }
        return $article;
    }
```

To be able to create something in our database. We need a form to input our data 
like we did with `formbuilders` in part 2 

Make a builder called `CommentType`.

Finally create a function called `create()` in our controller.

```
 public function create(Request $request)
    {

        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $entityManager = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $article = $form->getData();

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('blogger-notice', 'Your article was successfully saved. Thank you!');

        }
        return $this->render('Article/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
```

You can now try to create a create function for `category` and or `article`. 
If you would like to practice.

https://symfony.com/doc/current/doctrine.html - doctrine!




