## [Part 1] - Symfony5 Configuration and Templating

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
You will probably see some __errors__! 
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
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    
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
<?php

namespace App\Entity;

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
<?php
// src/Blogger/BlogBundle/Form/EnquiryType.php
namespace App\Form;

use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

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
<?php

namespace App\Mailer;

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

    public function getEmail()
     {
         return $this->email;
     }

    public function getName()
    {
        return $this->name;
    }

    public static function createEmailAddress($email,$name= null)
    {
        return new self($email,$name);
    }

    public function toString()
    {
        return implode(', ',[$this->getEmail(),$this->getName()]);
    }
}
```

An Example of a `value object`, and it is immutable. 
This means that after `EmailAdress` is created it can no longer change.
Which is allot safer to put in our `Mail` entity.

https://en.wikipedia.org/wiki/Value_object -- value object!

* Mail.php

```
<?php

namespace App\Mailer;

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


    public function getBody()
    {
        return $this->body;
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function getReceiver()
    {
        return $this->receiver;
    }

    public function getSubject()
    {
        return $this->subject;
    }

}
```


* MailerService.php

```
<?php

namespace App\Mailer;

use http\Exception;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Swift_Mailer;
use Swift_Message;
use Swift_TransportException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MailerService implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private $mailer;
    private $twig;

    public function __construct(Environment $twig, Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function sendMail(Mail $mail)
    {
        try {
            $message = new Swift_Message(
                $mail->getSubject(),
                $mail->getBody()
            );

            $message
                ->setFrom($mail->getSender()->getEmail())
                ->setTo($mail->getReceiver()->getEmail())
            ;

            $this->mailer->send($message);
        }
        catch (\Swift_TransportException $STe) {
            $errorMsg = "the mail service has encountered a problem. Please retry later or contact the site admin.";

            $this->logger->critical($errorMsg);
        }
    }

    public function renderTemplate($templateName, array $context)
    {
        try {
            return $this->twig->render(
                $templateName,
                $context
            );
        } catch (LoaderError $e) {
        } catch (RuntimeError $e) {
        } catch (SyntaxError $e) {
        }

        return false;
    }
}
```

we will use `swift mailer` to send our `Mail` entity.


In our `Event` directory we will make the file
* EnquiryEvent.php

```
<?php
namespace App\Event;
use Symfony\Contracts\EventDispatcher\Event;

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
<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Mailer\EmailAddress;
use App\Mailer\Mail;
use App\Mailer\MailerService;
use App\Event\EnquiryEvent;

class ContactPageEventSubscriber implements EventSubscriberInterface
{
    private $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService=$mailerService;
    }

    public function onCustomEvent(EnquiryEvent $event)
    {
        $enquiry= $event->getCode();

        $this->mailerService->sendMail(new Mail('Contact enquiry from symblog'
            ,EmailAddress::createEmailAddress('simsimpeeeters@gmail.com','blabla')
            ,EmailAddress::createEmailAddress('simsimpeeeters@gmail.com','blabla')
            ,$this->mailerService->renderTemplate('page/contactEmail.txt.twig', [
                'enquiry' => $enquiry,
            ])));
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

https://symfony.com/doc/current/event_dispatcher.html - event & subscriber!

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

* contactEmail.html.twig

```
{# src/symfony-boilerplate/templates/page/contactEmail.txt.twig#}
A contact enquiry was made by {{ enquiry.name }} at {{ "now" | date("Y-m-d H:i") }}.
Reply-To: {{ enquiry.email }}
Subject: {{ enquiry.subject }}
Body:
{{ enquiry.body }}
```

## [Part 3] -  The Blog Model: Using Doctrine 5

####Overview

his chapter will begin to explore the blog model. 
The model will be implemented using the Doctrine 5 Object Relation Mapper (ORM).
Doctrine 5 provides us with persistence for our PHP objects. 
It also provides a proprietary SQL dialect called the Doctrine Query Language (DQL)

* Doctrine mapping
* The blog model

####doctrine mapping

Before we can add or get data from our database. 
We first need to make our entities and make sure our mapping is done correctly

In the Entity directory we need to some files

* Article.php

```
<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

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

    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    public function  setUpdatedValue($updated)
    {
        $this->setUpdated(new \DateTime());
    }

    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;
    }

    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setSlug($slug)
    {
        $this->slug = $this->slugify($slug);
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('#[^\\pL\d]+#u', '-', $text);
        // trim
        $text = trim($text, '-');
        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }
        // lowercase
        $text = strtolower($text);
        // remove unwanted characters
        $text = preg_replace('#[^-\w]+#', '', $text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function addCategory(Category $category)
    {
        $collectionCategorie = $this->getCategories();

        if($collectionCategorie->contains($category))
        {
            return false;
        }

        $this->categories[]=$category;
    }

    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);
    }

    ...

}

```

* Category.php

```
<?php

namespace App\Entity;

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

    public function getCodeName()
    {
        return $this->code_name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

}
```

* Comment.php

```
<?php

namespace App\Entity;

class Comment
{

    protected $id;

    protected $user;

    protected $comment;

    protected $approved;

    protected $created;

    protected $updated;

    public function __construct()
    {

        $dateTime = new \DateTime();

        $this->setCreated($dateTime);

        $this->setUpdated(clone $dateTime);

        $this->setApproved(true);
    }

    public function update(string $comment)
    {
        $this->comment = $comment;

        $this->setUpdated(new \DateTime());
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }
    public function getComment()
    {
        return $this->comment;
    }

    public function setApproved($approved)
    {
        $this->approved = $approved;
    }

    public function getApproved()
    {
        return $this->approved;
    }

    ...

}

```

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
            <cascade>
                <cascade-persist />
            </cascade>
        </many-to-many>
        <many-to-many field="comments" target-entity="App\Entity\Comment">
            <cascade>
                <cascade-persist />
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
 
####the blog model







