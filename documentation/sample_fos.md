# Sample FOSUserBundle Setup

## Security.yml

    # app/config/security.yml
    security:
        encoders:
            FOS\UserBundle\Model\UserInterface: bcrypt

        role_hierarchy:
            ROLE_ADMIN:         ROLE_USER
            ROLE_SUPER_ADMIN:   ROLE_ADMIN

        providers:
            fos_userbundle:
                id: fos_user.user_provider.username_email

        firewalls:
            main:
                pattern: ^/
                form_login:
                    provider: fos_userbundle
                    csrf_token_generator: security.csrf.token_manager
                logout: true
                anonymous: true

        access_control:
            - { path: ^/$, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/admin, role: ROLE_ADMIN }

## Config.yml

### Swiftmailer

You'll want to configure Swiftmailer so that FOSUserBundle can send email to users:

    # app/config/config.yml
    swiftmailer:
        transport: "%mailer_transport%"
        host:      "%mailer_host%"
        username:  "%mailer_user%"
        password:  "%mailer_password%"
        spool:     { type: memory }

### FOS Configuration

    fos_user:
        db_driver: orm
        firewall_name: main
        user_class: AppBundle\Entity\User
        service:
            mailer: fos_user.mailer.twig_swift
        registration:
            confirmation:
                enabled: true
                template: FOSUserBundle:Registration:email.txt.twig
        resetting:
            email:
                template: FOSUserBundle:Resetting:email.txt.twig
        from_email:
            address: %email_from%
            sender_name: %email_sender%