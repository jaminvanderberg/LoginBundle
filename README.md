# Bootstrap 4 Template Layout for FOSUserBundle

This bundle creates a standardized layout for FOSBundle using Bootstrap 4.
It provides a welcome screen with a login form on the right, 

I created this bundle because I setup the same login templates for several different projects. I wanted
to keep the templates uniform in case I made any changes, while still allowing customization such as background,
logo, and welcome message.  This also helped pull a bunch of login templates out of my bundle, allowing me to better 
focus on the templates that are more relevant to the project.

If this is useful to someone else, great.  If not, then it's still useful to me.

If comes close to meeting your needs but doesn't quite cover everything, there are a few options.  You can override
the templates in your own bundle (see [documentation](documentation/override.md#templates)), you can fork this repository
and modify the templates directly, or you can add additional configuration options and do a push request.

## Installation

### Composer Install

First you need to install the bundle via Composer.  This will automatically load FOSUserBundle as well.

    composer require jaminv/login-bundle

or edit composer.json:

    # /composer.json
    "require": {
        ...
        "jaminv/login-bundle": "dev-master"
    },

Don't forget to:

    composer install

### Register Bundle

Then, you need to register the bundle and FOSUserBundle in your /app/AppKernel.php:

    # /app/AppKernel.php
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = [
                ...
                new FOS\UserBundle\FOSUserBundle(),
                new jaminv\LoginBundle\jaminvLoginBundle(),

                new AppBundle\AppBundle(),
            ];
            ...

### Import Routing Files

Then, you need to add the routing configurations for this bundle and FOSUserBundle:

    # app/config/routing.yml
    fos_user:
        resource: "@FOSUserBundle/Resources/config/routing/all.xml"
    
    jaminv_login:
        resource: "@jaminvLoginBundle/Resources/config/routing/routing.xml"

By default, this bundle will create a route for '/'.  This will be a customizable welcome page with a login
form on the right.  If you don't want this, see the [documentation on overriding default behavior](documentation/override.md#routing).

### FOSUserBundle Setup

There's a bit more that still needs to be setup for FOSUserBundle, and unfortunately this bundle can't do any of
this automatically.  For full details, see the [FOS Setup Documentation](http://symfony.com/doc/current/bundles/FOSUserBundle/index.html).

At this point, the bundle should already be enabled and the routing added, but you will also need to create a user class,
and add FOS sections to the config.yml and security.yml configuration files.

I won't cover creating the user class here because the [FOS Documentation](http://symfony.com/doc/current/bundles/FOSUserBundle/index.html)
already covers that well, but I do have a [sample FOS setup](documentation/sample_fos.md) that includes some additional
options for config.yml and security.yml that are recommended.

## Usage

### LoginBundle Configs

There are also a few simple configs for this bundle, all of which go in config.yml:

    # app/config/config.yml
    parameters:
        login_redirect: home

    twig:
        globals:
            image_bkg: "assets/image/login-bkg.jpg"
            image_logo: "assets/image/logo.png"
            logo_height: 225px
            logo_width: 300px


In the parameters section, you need to define the default route for a user once they have logged in.  This route will
not be used if they are redirected to another page.  It is used when they go to the main page (/) and login.  Here,
I've used the sample value 'home'.  It can be whatever route works best with your configuration.

In the Twig global configuration, you'll want to add some parameters that allow you to customize the look of the login/welcome
page.  `image_bkg` defines the (web relative) path for a high-resolution image to use for the background of the login page.
`image_logo` defines the (web relative) path for a logo image.  This image can be whatever size you want, but somewhere around 
300-400 pixels wide is probably ideal.  Specifying the logo height and width prevents the page from jumping when the logo
loads.  It is highly recommended that you do this.

### Templates

In the default setup, LoginBundle is looking for two twig templates:

* /app/Resources/view/base.html.twig
* /app/Resources/view/welcome.html.twig

#### base.html.twig

base.html.twig is a simple "framework" template that includes the stylesheets and javascripts for the site.
LoginBundle expects there to be a `body` block defined, which is where it will put its page contents.  A simple
body.html.twig might look like this:

    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8" />
            <title>{% block title %}{{ 'app.game_name'|trans() }}{% endblock %}</title>
            {% block stylesheets %}
                <link rel="stylesheet" href="{{ asset('assets/vendor/tether/dist/css/tether.min.css') }}">
                <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
                <link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/font-awesome.min.css') }}" >
            {% endblock %}
            <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
        </head>
        <body>
            {% block body %}{% endblock %}

            {% block javascripts %}
                <script src="{{ asset('assets/vendor/jquery/dist/jquery.js') }}"></script>
                <script src="{{ asset('assets/vendor/tether/dist/js/tether.min.js') }}"></script>
                <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
            {% endblock %}

        </body>
    </html>

Note that LoginBundle uses Bootstrap 4 and Font Awesome, so you will need to include those here.  Also note that
LoginBundle uses CSS to make the HTML, BODY, and its own elements have a height property of 100%.  If the `body`
block is contained inside an element that does not have a height of 100%, then the full page layout will not work
properly.  It's likely easier to just not but the `body` element inside an html element other than BODY.

#### welcome.html.twig

welcome.html.twig allows you define the welcome message that will show on the left side of the screen. By default,
the login form will be the right third of the page, and you can put a welcome message on the remaining two thirds of
the page.  Note that this message may not show up on low-resolution displays (like phones), which may only show the login
form.  So additional customization may be available here in the future.

A simple welcome.html.twig might look this:

    {% extends 'jaminvLoginBundle::welcome.html.twig' %}

    {% block welcome %}
        <div class="jumbotron">
            <h1 class="display-3">Welcome</h1>
        </div>
    {% endblock %}

You'd likely want to do more than this, but the framework is there.  You need to extend `jaminvLoginBundle::welcome.html.twig`,
and put your contents inside the `welcome` block.

#### Overriding Default Behavior

LoginBundle uses some pre-defined template and block names to make setup simple.  If these do not work for you,
please check out the [documentation for overriding default behavior](documentation/override.md#templates).


### Conclusion

That's it.  Once you've done all this, you should be able to go to / and see a nice welcome/login page.  You can register
from this page, and LoginBundle also defines a number of other FOS response pages.  If you'd like to change any of this 
behavior, check out the [documentation for overriding default behavior](documentation/override.md#fos).

Once you've logged in, you should be redirected to whatever route you set up in the configuration.
