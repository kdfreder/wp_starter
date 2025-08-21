WP Starter Theme

A simple, framework-agnostic starter theme for WordPress projects.
It provides a clean foundation for organizing HTML, CSS (Less or SCSS), and JavaScript, without enforcing specific frameworks or libraries.

Getting Started

The theme includes two starter HTML files:

index-less.html – for Less development

index-scss.html – for SCSS development

To choose one:

If using SCSS, delete index-less.html and rename index-scss.html → index.html.

If using Less, delete index-scss.html and rename index-less.html → index.html.

Each file includes a minimal <head> and <body>, plus references to a base JS library. The difference lies in how CSS is compiled.

CSS Setup
Less

index-less.html links directly to style.less, compiled in-browser via less.js.

This allows for quick, cross-device development without a build step.

Optionally, you can compile Less via Gulp, CodeKit, or PrePros.

SCSS

index-scss.html links to a compiled .css file in /assets/.

CSS is compiled with Gulp (or any preprocessor tool of your choice).

File Structure
/assets/   → Compiled & minified assets (not tracked in git)
/src/      → Source files & images (commit to git)
/tools/    → Gulp + build configs (commit gulpfile.js, less.js, package*.json)


Do not commit /assets/ to version control—your build pipeline will generate these.

Commit everything inside /src/ and essential configs in /tools/.

CSS Conventions

The theme is unopinionated on preprocessors but opinionated on structure.

/base/ → global styles (typography, forms, resets, utility classes)

/components/ → self-contained components with minimal specificity

Example:

<section class="section announcement">
  <div class="inner">
    <h3>Hello there!</h3>
    <p><strong>Announcement Title Here</strong> Curabitur blandit tempus porttitor.</p>
    <a href="#" class="button">Learn More</a>
  </div>
</section>


.section → generic defaults

.announcement → component-specific styles (components/announcement.scss)

Keep specificity low for flexibility and maintainability.

JavaScript

Use uncompiled JS during development.

Compile only when testing the build process.

Default library: jQuery (WordPress standard).

Place custom JS in script.js. If it grows too large, split into logical modules and load them after third-party libraries but before script.js.

Images

Place source images in /src/img/.

Reference them in CSS as /assets/img/ (where the optimized versions will be output).

Gulp Usage

Gulp handles compilation of CSS/JS and optimization of images. Run commands from /tools/.

Available tasks:

gulp → watch & compile JS + SCSS

gulp watchScss → watch SCSS

gulp watchAllScss → watch JS + SCSS + images

gulp watchLess → watch Less

gulp watchAllLess → watch JS + Less + images

gulp watchJs → watch JS

gulp watchImg → watch images

gulp scssTask / lessTask / jsTask / imgTask → one-off build & quit

Installation
cd tools/
npm install


Requires Node.js & npm
.

Running Locally

You can’t just double-click the HTML files—serve them with a local server.

Option 1: VSCode Live Server (GoLive!)
Option 2: http-server via npm:

npm install -g http-server
http-server


This will start a server in your project root.
More options: http-server docs
.
