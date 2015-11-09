# Morfy-Panel

Backend for Morfy CMS



## Instructions

**Require vars for backend Morfy Panel**

**backend email:**

    author:
      email: 'demo@gmail.com'

**backend options:**

    backend_folder: 'admin'

**password:**

    backend_password: 'demo'

**language:**

    backend_language: 'en'

**pagination:**

    backend_pagination_pages: 6
    backend_pagination_uploads: 6
    backend_pagination_media_all: 3
    backend_pagination_media: 16


**All vars:**

    author:
      # backend email
      email: 'demo@gmail.com'
    backend_folder: 'admin'
    backend_password: 'demo'
    backend_language: 'en'
    backend_pagination_pages: 6
    backend_pagination_uploads: 6
    backend_pagination_media_all: 3
    backend_pagination_media: 16

**Note:**

if you like ,you can change folder name of admin.
- rename $backend var in admin/index.php
- rename backend_folder in site.yml

Thats it !

## Json structure of media elements

    }
        "1447007935": {
            "id": 1447007935,
            "title": "The blue Worlds",
            "desc": "Many pics of blue worlds by uspslash.com",
            "thumb": "/public/media/album_thumbs/album_1447007935.jpg",
            "images": "/public/media/albums/album_1447007935",
            "tag": "blue",
            "width": "800",
            "height": "500"
        }
    }

You can use media plugin to show images:




# Morfy Media Plugin

Extends Media section in frontend of Morfy Panel

##Documentation

Create a media.md file on storage/pages folder with template media.tpl like this:

        ---
        title: Media example
        description: Media items for gallery or portfolio
        template: media
        ---


Create a media.tpl file on themes/default-theme folder like this:

File **media.tpl**

        {extends 'base.tpl'}
        {block 'content'}
            <div class="container">
                {Morfy::runAction('Media')}
            </div>
        {/block}