## [Part 5] - Migrations & Unit testing and Translations 

#### migrations

`Database migrations` are a way to safely update your `database schema` both locally 
and on production. Instead of running the `doctrine:schema:update command` or 
applying the database changes manually with SQL statements, migrations allow to 
replicate the changes in your database schema in a safe manner.

https://symfony.com/doc/master/bundles/DoctrineMigrationsBundle/index.html -- migrations!

#### unit testing

A `unit test`. is meant to be a test on a unit (like the name says). 
What we mean by that is that we don't want our unit test 
can't connect to our database or 3rd party software.

We can however solve this with `dependency injection`. 

Unit tests can make our code easier to read, easier to debug 
and even make us code better. 

Also we don't make unit tests for our controller's. 
that's not necessary. 

In your `test` directory make a directory for your `util` functions, 
`events` and `subscribers`. In these you can make your files.

your functions need to start with `test`.

```
class SlugifyTest extends TestCase
{
    public function testSlugify()
    {
        $slug = new Slug();
        $result = $slug->slugify('Symfony');

        $this->assertEquals('symfony',$result);
    }

    /**
     * @dataProvider provideSlug
     */
    public function testSlugAlot($title)
    {
        $slug = new Slug();
        $result = $slug->slugify($title);
        $this->assertNotEquals($title,$result);
    }

    public function provideSlug()
    {
        return [['SyMfony'],['AKANEO'],['weB'],['HtMl']];
    }
}
```

In this example we will use mocking to test if our `EnquirySubscriber` 
is working. because our real subscriber sends an email. 
And that doesn't matter to us here. So we create a mock $mailer 
that will act like it is an email service but isn't really.

Another important thing to note is if you look at our 
`ContactPageEventSubscriberTest` and `ContactPageEventSubscriber`.
You will see that they take the exact same steps.

```
class ContactPageEventSubscriberTest extends TestCase
{
    public function test_it_should_send_an_email_from_an_inquery_event()
    {
        $mailer = $this->prophesize(MailerInterface::class);

        $subscriber = new ContactPageEventSubscriber($mailer->reveal());

        $event = $this->prophesize(EnquiryEvent::class);
        $event->getEnquiry()->willReturn($this->createEnquiry());

        $subscriber->onEnquirySubmitted($event->reveal());

        //$mailer->send(Argument::any())->shouldBeCalled();

        $mailer->send(Argument::type(TemplatedEmail::class))->shouldBeCalled();
    }

    private function createEnquiry()
    {
        $enquiry = new Enquiry();
        $enquiry->setBody('body');
        $enquiry->setSubject('subject');
        $enquiry->setEmail('email@example.com');
        $enquiry->setName('name');

        return $enquiry;
    }
}
```

https://symfony.com/doc/current/testing.html -- unit testing!

#### translations

The term `internationalization` (often abbreviated i18n) refers to the 
process of abstracting strings and other locale-specific pieces out of your 
application into a layer where they can be translated and converted based on 
the user's locale (i.e. language and country). For text, this means wrapping 
each with a function capable of translating the text (or "message") 
into the language of the user:


The short version is. We are not gonna make a new separated 
twig for each language. Instead we will put a label instead. 
and then give that label a new value for each language in our 
translation folder 


```
framework:
    default_locale: 'en'
    translator:
        paths:
            - '%kernel.project_dir%/translations'
        fallbacks:
            - 'en'
```

After you install the translation bundle. 
you will need to type this in your `translation.yaml` file.
You can find it in your config\package directory.

You will also see a directory added in your root called `translations`. 
Here you need to create a new yaml file for every language. We will add.

* messages.en.yml  

```  
blog:                                                                                                                         
      home_nav_title: Home
      about_nav_title: About
      contact_nav_title: Contact
      title: Creating a blog in Symfony
      created.by: Created by
      continue.reading: Continue reading ...
      comments: Comments
      tags: Tags
      posted.by: Posted by
      no.articles: There are no article entries for symblog
      categories: Categories
      no.categories: There are no categories
      tag.cloud: Tag cloud
      no.tags: There are no tags
      latest.comments: Latest comments
      commented.on: Commented on
      no.comments: There are no recent comments
      contact.title: Want to contact symblog?
      image: Photo not found
      add.comment: Add comment
      add.comment.post: Add comment for blog post
      first.comment: There are no comments for this article. Be the first ...
```

* messages.nl.yml

Just replace the the text to dutch and it's done for this file aswell.  

```
 <h3>{{ trans('blog.about') }}</h3>
```

Use this in your twig files. 

In this example you will replace `blog.comments` with comments 

https://symfony.com/doc/current/translation.html -- translations!