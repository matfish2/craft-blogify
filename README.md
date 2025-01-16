# Blogify

Blogify offers a single-click solution to the task of building a blog with CraftCMS.

Once installed the plugin creates the data structure and templates, and allows for seeding of dummy posts, so you can
start customizing your blog right away. Blogify comes with a bunch of Twig enhancements that make the code in your
templates declarative and simple.

#### Click [here](https://www.craftcmsplugins.com/blog/index) for a full demo of the result with zero configuration.

## Installation

1. Include the package:

```
composer require matfish/craft-blogify
```

2. Install the plugin:

```
php craft plugin/install blogify
```

3. (Optional but recommended for development) Seed some posts:

```
php craft blogify/seed
```

The default is 10 posts. You can pass a `--count` option to seed as many as you like.

That's all! Now go to `{yoursite.dev}/blog/index` and see the blog in action.

## Spec

Blogify adds the following data structures to Craft:

1. A "Blog Listing" (or index) Single.
2. A "Blog" Channel
3. An "Author Page" Single (All author posts)
4. A "Blog Categories" category group and a corresponding category template to display all posts related to a given
   category.
5. A "Blog Tags" tag group
6. A "Tag Page" single (All posts with a given tag)
7. A "Blog Assets" assets volume.
8. A "Blog Thumbnail" transform.

Each Post in the Blog channel has the following fields:

1. Title
2. Image
3. Excerpt
4. Content (a Redactor field)
5. Categories
6. Tags

## Twig Enhancements

the initial theme demonstrates usage of all the enhancements below.

### Global variables

Blogify exposes a global `blogify` variable with the following properties:

* `categories` All blog categories
* `usedCategories` All blog categories that have at least one post associated with them
* `tags` All blog tags

### Global methods

* `blogifyRecentPosts` gets recent posts. E.g `blogifyRecentPosts().limit(4).all()`
* `blogifySearch` searches the blog (title and content fields). E.g `blogifySearch('foo')`
* `blogifyPopularPosts` get most popular posts (see [below](#record-post-views)). 

In addition, entities contain contextual methods, as follows:

#### Entry (Post)

* `categories` get post categories
* `relatedPosts` get other posts with one of the given post's categories
* `tags` get post tags
* `next` get next post
* `prev` get previous post
* `image` get post image
* `thumbnail` get post thumbnail (defined under Settings->Assets->Image Transforms->Blog Thumbnail)
* `excerpt` get post excerpt
* `content` get post content (supports Matrix field rendering. See below)

#### Category

* `posts` get posts related to the given category

#### Tag

* `posts` get posts that have the given tag

#### User (Author)

* `posts` get author posts

Note: Unless the method is getting a single entity (e.g `entry.image`), Blogify will return a query object, which can be
used to further augment the query, e.g `author.posts.limit(3).all()`.

## Using Matrix field for the post content

By default the provided Post Content field is a Redactor field. If you wish to use the powerful Matrix field instead,
we've got your back:

* Go to Settings->Fields->Post Content
* Change field type to Matrix and build your blocks.
* Add a `_matrix` folder under `blogify/post`
* Add a template for each block type, named after the handle.

Each partial will expose a `block` variable containing all the fields. E.g if your block handle is `postHeading` and it
has a `heading` field, create a `blogify/post/_matrix/postHeading.twig` file:

```html
    <h2>{{block.heading}}</h2>  
```

* Render the post content:

```html

<div class="post-content">
    {{ entry.content | raw }}
</div>
```

Note that you can change the templates folder path via the plugin's config. Create a `config/blogify.php` file:

```php
return [
     // matrix templates path relative to the templates folder
    'matrixTemplatesPath'=>'my/matrix/path'
];
```

## Record post views

Blogify allows you track the popularity of your posts, so you can sort by popularity
and display number of views. This option is disabled by default. You can enable it with a single command:

```
php craft blogify/views/record
```

This will add a read-only Post Views field to your posts (right after the title). Now, every time someone views the post
Blogify will automatically increment the number of views, while excluding drafts and previews.

### Excluding IPs 
Since you probably don't want to record your own views you can exclude certain IPs from triggering the event by adding the following
to `config/blogify.php`:

```php
return [
   'postViewsExcludeIps'=>[
       '192.168.10.1'
  ]
];
```

Now you can sort by popularity (in descending order of course) in your templates:
```
blogifyPopularPosts().limit(5).all()
```
To exclude posts with no views from the query pass `true` to the method call:
```
blogifyPopularPosts(true).limit(5).all()
```
Finally, to display the number of views in your template simply call:
```
{{entry.views}}
```

### Multiple sites
When installed Blogify will register all Category Groups and Sections to all existing sites,
with the same URL (relative to site root URL) and same templates.

If you need to vary template content between sites you can use:
1. [Static translations](https://craftcms.com/docs/4.x/sites.html#static-message-translations)
2. [If statements](https://craftcms.com/docs/4.x/sites.html#language)
3. [Polymorphism](https://craftcms.com/docs/4.x/sites.html#language)

In case you still need to duplicate an entire template, you can duplicate it to your site folder, *while keeping the same folder structure* ** (e.g `templates/de/blogify/listing/_entry.twig`)
This will prompt Craft to load this template as an override, when accessing the site with a handle of `de`.
See: https://craftcms.com/docs/4.x/sites.html#step-4-update-your-templates

** Note that you can also duplicate it to a path that doesn't retain the same folder structure, in which case you'll need to define a new template path for the relevant Section/Category Group in Craft's back-end.

## Modification

Blogify is designed as a solid starting point for a blog. You can freely extend and modify the data structures and
templates, while keeping the following in mind:

* Don't change the handles on any of the entities.
* Don't delete any data structures.
* Uninstalling the plugin will remove all data including the `blogify` templates folder.

## License

You can try Blogify in a development environment for as long as you like. Once your site goes live, you are required to
purchase a license for the plugin. License is purchasable through
the [Craft Plugin Store](https://plugins.craftcms.com/blogify).

For more information, see
Craft's [Commercial Plugin Licensing](https://craftcms.com/docs/3.x/plugins.html#commercial-plugin-licensing).

## Requirements

This plugin requires Craft CMS 3.7.0 or later.

## Issues and Discussions Guidelines

*Please only open a new issue for bug reports.*
For feature requests and questions open a new [Discussion](https://github.com/matfish2/craft-blogify/discussions) instead.
When discussing a feature request please precede [FR] to the title.
