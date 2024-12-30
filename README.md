# Blog Project:

This blog have Users and Posts and comments on posts, its a simple project to test my laravel skills.

# API Documentation:

## User:

-   Login: domain/api/login ::Post
-   Registe: domain/api/register ::Post

## Post:

-   Index: domain/api/posts ::Get

-   Index: domain/api/posts?filter[creator_id]=<value> ::Get

-   Index: domain/api/posts?sort=content ::Get
-   Index: domain/api/posts?sort=-content ::Get
-   Index: domain/api/posts?sort=created_at ::Get
-   Index: domain/api/posts?sort=-created_at ::Get
-   Index: domain/api/posts?sort=updated_at ::Get
-   Index: domain/api/posts?sort=-updated_at ::Get

-   Index: domain/api/posts?page=<page_no> ::Get
-   Index: domain/api/posts?page[size]=10 ::Get

-   Create: domain/api/posts ::Post
-   Show: domain/api/posts/{id} ::Get
-   Update: domain/api/posts/{id} ::Put
-   Delete: domain/api/posts/{id} ::Delete

# User Authentication and Authorization:

    ## Authentacated User:
        * can see posts,
        * can create new posts,
        * can update only posts that user is creator of; else unauthorized response will show up,
        * can delete only posts that user is creator of; else unauthorized response will show up.

    ## Unuathentacated User:
        canot see, create, update, or delete any post, and will recive unauthentacated response for every action.
