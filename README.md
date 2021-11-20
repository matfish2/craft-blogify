# Blogify

Blogify offers a single-click solution to the task of building a blog with CraftCMS.

Once installed the plugin crates the data structure and templates, and allows for seeding of fake posts, so you can start customizing your blog right away. Blogify comes with a bunch of Twig enhancements that make the code in your templates declarative and simple.

Check out the result [here](https://blogify.frb.io/blog/index). 

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
4. A "Blog Categories" category group and a corresponding category template to display all posts related to a given category.
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

In addition, entities contain contextual methods, as follows:

#### Entry (Post)

* `categories` get post categories
* `relatedPosts` get other posts with one of the given post's categories
* `tags` get post tags
* `next` get next post
* `prev` get previous post
* `image` get post image
* `excerpt` get post excerpt
* `content` get post content (supports Matrix field rendering. See below)

#### Category

* `posts` get posts related to the given category

#### Tag

* `posts` get post that have the given tag

#### User (Author)

* `posts` get author posts 

Note: Unless the method is getting a single entity (e.g `entry.image`), Blogify will return a query object, which can be used to further augment the query, e.g `author.posts.limit(3).all()`.

## Using Matrix field for the post content 

By default the provided Post Content field is a Redactor field.
If you wish to use the powerful Matrix field instead, we've got your back:
* Go to Settings->Fields->Post Content
* Change field type to Matrix and build your blocks.
* Add a `_matrix` folder under `blogify\post`
* Add a template for each block type, named after the handle. 

Each partial will expose a `block` variable containing all the fields.
E.g if your block handle is `postHeading` and it has a `heading` field,
create a `blogify/post/_matrix/postHeading.twig` file:

```html
    <h2>{{block.heading}}</h2>  
```

* Render the post content:

```html
<div class="post-content">
    {{ entry.content | raw }}
</div>
```

Note that you can change the templates folder path via the plugin's config.
Create a `config/blogify.php` file:

```php
return [
     // matrix templates path relative to the templates folder
    'matrixTemplatesPath'=>'my/matrix/path'
];
```


### Modification
Blogify is designed as a solid starting point for a blog.
You can freely extend and modify the data structures and templates, while keeping the following in mind:

* Don't change the handles on any of the entities.
* Don't delete any data structures.
* Uninstalling the plugin will remove all data including the `blogify` templates folder.

