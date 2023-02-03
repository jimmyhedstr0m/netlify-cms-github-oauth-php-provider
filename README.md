# Netlify CMS GitHub OAuth PHP provider

This repository is heavily inspired by [vencax/netlify-cms-github-oauth-provider](https://github.com/vencax/netlify-cms-github-oauth-provider) which reverse engineers the default client of Netlify CMS. This is the PHP version which can be used to authenticate against GitHub when deploying Netlify CMS admin interfaces in various web hosting services such as [one.com](https://one.com) and [simply.com](https://simply.com) etc. Since GitHub requires a server for authentication, this PHP clone can be used together with already existing web servers.

This PHP client is entirely focused on the GitHub authentication part and does not support, for instance, Gitlab at the moment.

## Create Oauth App

Information about how to create the OAuth App can be found here [Creating an OAuth App](https://docs.github.com/en/developers/apps/building-oauth-apps/creating-an-oauth-app).

- In **Homepage URL** field enter `https://yourdomain.com`
- In **Authorization callback URL** field enter `https://yourdomain.com/callback.php`

## Auth Provider Config

Configuration is done by replacing `__CLIENT_ID__` and `__CLIENT_SECRET__` with your real OAuth credentials given by GitHub in `vars.php`. It's up to you how you want to access your environment variables. If they should be provided through dotenv or be hard coded, as long as you keep them safe.

## Deploy files

Upload `auth.php`, `callback.php` and `vars.php` to you web server so that they can be reachable on for instance `https://yourdomain.com/auth.php`,

## Netlify CMS Config

Update and deploy your Netlify CMS `config.yml` file with something like this:

```
backend:
  name: github
  repo: username/repo
  branch: main
  base_url: https://yourdomain.com
  auth_endpoint: auth.php
```

Now you should be able to login to Netlify CMS admin interface through `https://yourdomain.com/admin`.
