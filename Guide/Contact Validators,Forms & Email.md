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
    public function ContactPage(Request $request,MailerInterface $mailer)
    {
        $enquiry = new Enquiry();
        $form = $this->createForm(EnquiryType::class,$enquiry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $email = (new Email())
                    ->from('fromemail@example.be')
                    ->to('email@example.be')
                    ->subject('example')
                    ->text('blabla blabla bla');

            $this->mailer->send($email);

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

then we check in an if statement if the form is submitted and if its valid. 
If it is valid we want to send an email. 

#### Validation

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

#### Events & Subscribers

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

First delete what we did to send a mail in `PageController` and replace it with this. 

```  
$this->eventDispatcher->dispatch(new EnquiryEvent($enquiry), EnquiryEvent::ENQUERY_CREATED); 
```
This will trigger our event.  

```
class EnquiryEvent extends Event
{
    public const ENQUERY_CREATED = 'enquery_created';

    private $code;

    public function __construct($code)
    {
        $this->code = $code;
    }
    public function getEnquiry()
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

    public function onEnquerySubmitted(EnquiryEvent $event)
    {
        $enquiry= $event->getEnquiry();
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
                ['onEnquirySubmitted', 10],
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


```
  framework:
    messenger:
    transports:    
          email: "doctrine://default"
    
    routing:
         Symfony\Component\Mailer\Messenger\SendEmailMessage : email
```

If you want to send your emails assync add this to `messenger.yaml`.

The email won't be send out immediately. 
First they wil get into a waiting line. And even if they fail 
you will be able to  send them again later.

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