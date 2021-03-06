# Example Codebase for a Tech Test - Food Standards Agency API Integration

This codebase was originally written to serve as a tech test for a client, to show I know my way
around PHP and React.js. Now, it's here for everyone to enjoy!

# Original Requirements and Features

This codebase was written to cover the following user story;

> AS AN end user
> I WANT to see a breakdown of the hygiene rating of all establishments, distributed by percentage, for a given local authority
> IN ORDER to get a clear overview of the "profile" of all the establishments of that local authority

When the app loads, a request is made to load all the local authorities. From there, you can click
and search in the top dropdown for a local authority. Once that is done, the relevant profile data
will be requested from the API and displayed in the table.

## What?

This git repo covers multiple discrete codebases, so it's a little different from normal.

`server` is a PHP, SLIM3 API that talks to the [Food Standards Agency](http://api.ratings.food.gov.uk/help) API and acts as a "middleman" to
parse the responses into something more directly usable via a frontend.

`client` is a React.js frontend. It speaks to the `server` and displays the results it's given.

In order to run the codebases, it is expected that you have Vagrant, Composer, Node and NPM.

# Setup

### Server/API

- `cd` into `/server`
- `composer install` to get all the dependencies
- `vagrant up` to spin up the copy of Scotchbox
- your box will now be up and running at the default Scotchbox IP, http://192.168.33.10

You can alter the values in `.env` to alter FSA API details, and to alter the number of seconds responses are cached for.

### Client/React.js SPA

- `cd` into `/client`
- `npm install` to get all the dependencies
- `npm start` to spin up the virtual Node server
- you can now browse to http://localhost:3000 and interact with the APP

The app was written to point its requests to http://192.168.33.10. If you change the IP address of the API,
remember to change where the client points to!
