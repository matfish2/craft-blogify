<?php


namespace matfish\Blogify;


interface Handles
{
    const PLUGIN = 'blogify';
    const CHANNEL = 'blogify';
    const LISTING = 'blogifyListing';
    const ASSETS = 'blogifyAssets';
    const CATEGORIES = 'blogifyCategories';
    const TAGS = 'blogifyTags';
    const TAG_PAGE = 'blogifyTagPage';
    const AUTHOR_PAGE = 'blogifyAuthorPage';
    const THUMBNAIL_TRANSFORM = 'blogifyThumbnail';

    const POST_CONTENT = 'blogifyPostContent';
    const POST_EXCERPT = 'blogifyPostExcerpt';
    const POST_IMAGE = 'blogifyPostImage';
    const POST_TAGS = 'blogifyPostTags';
    const POST_CATEGORIES = 'blogifyPostCategories';

    // Field group does not have a handle
    const BLOG_FIELDS_GROUP_NAME= 'Blog Fields';

    // optional. Generated via blogify/views/record command
    const POST_VIEWS = 'blogifyPostViews';

}