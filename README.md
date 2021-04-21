# Fig & Bloom Child Theme for North Commerce (by Fuel Themes)

## About

JS and CSS source files are located in the `/src` directory. These assets must be compiled, minified, etc prior to release. Instructions below.

## Build Tools

We use Laravel Mix as a convenient and user-friendly wrapper for webpack. Install Laravel Mix with the following instructions 

(Link me)[http://someaddress.com]

## Build Process

To create a build (ie. optimise JS and CSS assets) run `npx mix`

## Release Process

Continuous Integration has been configured for this project. 

When changes are merged into **Develop** branch then the staging server is automatically updated.

When changes are merged into **Master** branch then the production server is updated.