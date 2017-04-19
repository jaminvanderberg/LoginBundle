# Bootstrap 4 Template Layout for FOSUserBundle

This bundle creates a standardized layout for FOSBundle using Bootstrap 4.
It provides a welcome screen with a login form on the right, 

## Installation

### Composer Install

    composer require jaminv/aclfilter-bundle

or edit composer.json:

    # /composer.json
    "require": {
        ...
        "jaminv/loginfilter-bundle": "dev-master"
    },

### Register Bundle

    # /app/AppKernel.php
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = [
                ...
                new jaminv\LoginBundle\jaminvLoginBundle(),

                new AppBundle\AppBundle(),
            ];
            ...

## Usage

### FOSUserBundle Setup

LoginBundle depends on FOSUserBundle, which handles all the heavy lifting. 
FOSUserBundle still needs to be setup using the instructions here:
(http://symfony.com/doc/current/bundles/FOSUserBundle/index.html)

It is recommended that you use the security provider fos_user.user_provider.username_email:

    # app/config/security.yml
    security:
        providers:
            fos_userbundle:
                id: fos_user.user_provider.username_email

### Templates

LoginBundle overrides FOSUserBundle's layout.html.twig in the following way:

    {% extends 'base.html.twig' %}

    {% block body %}
        {% block fos_user_content %}{% endblock %}
    {% endblock %}

So it's looking for a base.html.twig in the main resources folder, with a "body" block
defined in it.  If this does not work with your templating system, you will need to create
a Resources/FOSUserBundle/views folder and create a custom override for layout.html.twig
in it.





Set up a basic query:

    $em = $this->getDoctrine->getManager();
    $builder = em->getRepository('AppBundle:SomeTable')->getQueryBuilder('a');
    $builder->select('a')
        ->where("a.somefield = 'value'");

Apply the AclFilter to the query:

    $aclfilter = $this->get('jaminv.aclfilter');
    $query = $aclfilter->apply($builder->getQuery(), array('EDIT'), $this->getUser(), 'a');
    $result = $query->getResult();

The AclFilter will modify the query so that it only returns results for objects
for which the current user has EDIT permissions.

### AclQuery Usage

Using AclQuery to list users/roles that have access to an object can be done
in a single line:

    $result = $this->get('jaminv.aclquery')->queryAcl("AppBundle\\Entity\\SomeTable", $id);

The result is an array that might look something like this:

    [{"security_identifier":"ROLE_ADMIN","is_username":"0","mask":"32"},
    {"security_identifier":"AppBundle\\Entity\\User-username","is_username":"1","mask":"128"}]

Each entry in the array has 3 fields:

* security_identifier: (string) The security identifier, which will usually be "AppBundle\\Entity\\User-<username>" or "ROLE_<role>"
* is_username: (boolean) Will be 1 if the security identifier is a user identified by its username.  Will be 0 otherwise, which should signify that it is a ROLE.
* mask: (int) The permission mask for that user. Compare against MaskBuilder masks; if this number is greater than the MaskBuilder mask, the user has those permissions.

In the above example, the mask values indicate that the user "username" has been
granted OWNER permissions, while the role ROLE_ADMIN has been granted MASTER
permissions.

`AclQuery::queryAcl` also accepts a third, optional, parameter which is a field
name.  This can be used to perform the same operation for field-level permissions.
Note that if you do not include this parameter, the query will only return
object-level permissions and will not return field-level permissions.  Likewise,
using this parameter will only return field-level permissions.

It is recommended that you check that the user has GRANT permissions on
the object before returning the results of this query to them.  The AclQuery
service does not explicitly do that.

The AclQuery service does not currently traverse role hierachies or object ancestors.
It returns only direct object identity <-> security identity relationships.
There are currently no plans to add this functionality, as the current use
case (displaying permissions to the user for editing) only applies to direct
relationships.
