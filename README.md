> [!NOTE]  
> As of September 2022, Webex added official support for the OAuth Device Code grant flow. To know more, please see the documentaion on [Login with Webex - Getting an Access Token with Device Grant Flow](https://developer.webex.com/docs/login-with-webex#getting-an-access-token-with-device-grant-flow) and the blog post [Login with Webex on Developer Applications and 3rd Party Platforms](https://developer.webex.com/blog/login-with-webex-on-developer-applications-and-3rd-party-platforms).

QR Code based Authentication
============================
**Seamlessly authenticate with Webex on systems with limited web or input capability.**

This proof-of-concept application lets a registered user authenticate with Webex by scanning a Quick Response (QR) Code on their personal device, such as smartphone. In doing so, it essentially emulates an end user experience similar to using [OAuth 2.0 Device Authorization Grant](https://oauth.net/2/device-flow/), [RFC8628](https://tools.ietf.org/html/rfc8628). For example:
> Your organization deploys Smart TVs in conference rooms that run a Webex integration. Using this PoC, users can easily authenticate with Webex by scanning a QR Code displayed on the TV screen with their smartphone and and following the login prompt. This eliminates the need for cumbersome text input on the TV, providing a smooth and efficient authentication process.

The target audience for this PoC are [Webex Integration](https://developer.webex.com/docs/integrations) Developers who want a seamless way to sign-in their users with Webex on devices with limited web or input capability — Smart TVs, in-car displays, or apps running on RoomOS devices (in kiosk mode).


<p align="center">
    <a href="https://app.vidcast.io/share/432b5b0a-48dd-4197-9aa7-cf6e60f17003" target="_blank">
        <img src="https://user-images.githubusercontent.com/6129517/152443487-9bce59e6-1704-4d3d-82f5-15c65592afc4.gif" alt="qr-code-based-authentication-demo"/>
    </a>
</p>

<!-- ⛔️ MD-MAGIC-EXAMPLE:START (TOC:collapse=true&collapseText=Click to expand) -->
<details>
<summary>Table of Contents (click to expand)</summary>

  * [Overview](#overview)
  * [Setup](#setup)
  * [Demo](#demo)
  * [Disclaimer](#disclaimer)
  * [License](#license)
  * [Support](#support)

</details>
<!-- ⛔️ MD-MAGIC-EXAMPLE:END -->

## Overview
At it's core, the application uses [Temporary Signed URLs](https://laravel.com/docs/8.x/urls) as unique login links.

When someone visits the login page, a unique Temporary Signed URL is generated for them and is 
encoded as a QR code on the client side. The user scans this code on their personal device like 
a phone and begins the traditional Webex Login flow on there.

Meanwhile, the client with the QR code also polls the server at regular intervals to see if the 
user has successfully logged in. The server will only respond when the login flow on the user's 
device is successful.

The application has validation checks to ensure that Signed URLs are not fake, and can be 
used only once.

## Setup

These instructions assume that you have:
- [Docker installed](https://docs.docker.com/engine/install/) and running on a Windows (via WSL2), macOS, or Linux machine.

Then open and new terminal window and follow the instructions below.

1. Clone this repository and change directory:
   ```
   git clone https://github.com/WXSD-Sales/qr-code-based-authentication && cd qr-code-based-authentication
   ```

2. Rename `.env.example` file to `.env` (you may also edit your database credentials within this renamed file):
   ```
   mv .env.example .env
   ```

3. Review and follow the [Registering your Integration
   on Webex](https://developer.webex.com/docs/integrations#registering-your-integration) guide.
    - Your registration must have (at-least) the following [Webex REST API scopes](https://developer.webex.com/docs/integrations#scopes):
      | Scope                   | Description                                   |
      |-------------------------|-----------------------------------------------|
      | spark-admin:people_read | Access to read your user's company directory  |
      | spark:kms               | Permission to interact with encrypted content |
    - Use these Redirect URIs:
        - `https://localhost/auth/webex/callback`
        - `http://localhost/auth/webex/callback`
    - Take note of your Client ID and Client Secret. Assign these values to the `WEBEX_CLIENT_ID` and `WEBEX_CLIENT_SECRET` environment variables within the `.env` file respectively.

4. Optionally, review and follow the [Creating a Webex Bot](https://developer.webex.com/docs/bots#creating-a-webex-bot)
   guide. Take note of your Bot ID and Bot access token. Assign these values to the `WEBEX_BOT_ID` 
   and `WEBEX_BOT_TOKEN` environment variables within the `.env` file respectively.

5. Install Composer dependencies for the application.
   ```
   docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
   ```

6. Start the Docker development environment via [Laravel Sail](https://laravel.com/docs/8.x/sail):
   ```
   ./vendor/bin/sail up -d
   ```

7. Initialize the database for the application.
   ```
   ./vendor/bin/sail php artisan migrate:fresh
   ```

8. Install NPM dependencies for the application.
   ```
   ./vendor/bin/sail npm install
   ```

9. Run [Laravel Mix](https://laravel.com/docs/8.x/mix)  
   When you run this command, the application's CSS and JavaScript assets will be compiled and placed in the application's public directory:
   ```
   ./vendor/bin/sail npm run dev
   ```

Lastly, navigate to `http://<YOUR_LOCAL_IP_ADDRESS>/login` in your browser.


## Demo

A video where I demo this PoC is available on Vidcast — [https://app.vidcast.io/share/1f414f78-0faa-41ee-8ca3-94d03e9da9b3](https://app.vidcast.io/share/1f414f78-0faa-41ee-8ca3-94d03e9da9b3).

## Disclaimer

Everything included in this repository is for demo and Proof of Concept (PoC) purposes only. Use of the PoC is solely
at your own risk. This project may contain links to external content, which we do not warrant, endorse, or assume
liability for. This project is for Cisco Webex use-case, but is not official Cisco Webex branded project.

## License

[MIT](./LICENSE)

## Support

Please reach out to the WXSD team at [wxsd@external.cisco.com](mailto:wxsd@external.cisco.com?cc=ashessin@cisco.com&subject=QR%20Code%20based%20Authentication) or contact me on Webex (ashessin@cisco.com).
