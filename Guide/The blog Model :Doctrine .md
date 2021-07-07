## [Part 3-4] -  The Blog Model: Using Doctrine

#### Overview

his chapter will begin to explore the blog model. 
The model will be implemented using the Doctrine 5 Object Relation Mapper (ORM).
Doctrine 5 provides us with persistence for our PHP objects. 
It also provides a proprietary SQL dialect called the Doctrine Query Language (DQL)

* Doctrine mapping
* Data fixtures
* The blog model

#### doctrine mapping

Before we can add or get data from our database. 
We first need to make our entities and make sure our mapping is done correctly.

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
 
#### data fixtures

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

#### data model

In the `page` directory of the templates directory. Make the file. 

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

Then make some controllers to work in.

* ArticleController.php
* CategoryController.php
* CommentController.php

We will work in our `ArticleController.php`

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
        $article = $this->getArticleRepository()->findOneBy(['slug'=> $slug]);
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
