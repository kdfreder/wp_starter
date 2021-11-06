# Vertigo Music
Wordpress Starter Theme

Starter Theme is a simple, framework agnostic approach to origanizing your markup and css. It includes two starter html files, index-less.html and index-scss.html.

If you wish to work with SCSS simply delete the index-less.html file, and if you prefer Less simply delete index-scss.html then dename your file to index.html.

Both files contain a simple base of <head> and <body>, along with references to a standard JS library. The diferences is in their association to the preprocessor.

index-less.html
This file does not include a link to a compliled stylesheet. Instead, it links to the style.less file. This stylesheet (and it's @imports) are compiled in the browser. This makes Less slightly faster, and easier to develop cross-devices as it does not require compilation.

If you prefer to use gulp to compile your Less rather than use the in browser compilation, you can do so as noted below in the Gulp section. You may also use CodeKit or PrePros but that is outside the scope of this project.

index-scss.html
This file links to a single compiled .css file inside the /assets/ folder within the project. The .css file is compiled by gulp (or CodeKit or PrePros if you prefer), and placed here.

File Structure
/assets/ the folder contains all the compiled and minified assets for the site. The content of the folder should not be commited to git, nor manually deployed to a server. The build pipeline will generate and deploy all necessary files.

/src/this folder and it's subfolders contain the source files and images that are compiled and dumped into /assets/. Everything within this folder should be commited to git.

/tools/ contains the gulp and pkg files necessary to compile for dev when using gulp or the build pipeline. It also contains less.js which compiles the JS directly in browser when in dev mode. gulpfile.js, less.js, package-lock.json, and package.json should be commited to git. no other file or folder should be versioned.

CSS (both Less and SCSS)
As noted above, Starter Theme is unopinionated on choice of preprocessor and frameworks / libraries (in as much as one of those libraries isn't Tailwind). However it is opinioned on the manner in which your CSS is structured.

Structure
The /base/ folder contains the basic components for styling. These will contain the broad stroke styles that are applicable to most of the basic elements. For example you'll set the base font, line heights, typical heading styles, etc in _typography. Generic classes typically would live inside _base, and general form styling in _forms.

CSS should be structured into components. Take the following image as an example.

Structure Example

The each noted section (component) would inheret the generics defined in the _base folder files, and the specifics of that component would be defined in their respective component/* file such as components/media-feature.scss.

Furthering that example, the outer wrapping class on the <section> tag would inherit it's generic padding and margins from _base/, as would the inner <div> tag that wraps the content inside. Those would be overridden by the specific styles in the component.

Have look a short code example of a small component.

<section class="section announcement">
	<div class="inner">
		<h3>Hello there!</h3>
		<p><strong>Announcement Title Here</strong>Curabitur blandit tempus porttitor. Etiam porta sem <a href="#">malesuada magna mollis</a> euismod. Maecenas sed diam eget. </p>
		<a href="#" class="button">Learn More</a>
	</div>
</section>
In this short example, .section would be generic and provide defaults that apply to most sections of this type. .announcement would match the component name components/announcement.scss, and be used as the parent for the specific styles of this component. If this component differed from the generic, and had a background image that would be styled on .announcement. If the h3 tag differed from generic that would be styled as .announcement h3.

This gives us a significant level of control while maintaining flexibility. We can really max that out by keeping specificity as low as possible.

Specificty
In general the minimum level of specificity is to be maintained. Referencing the examples above, one might be tempted to write highly specific CSS, for example:

.section.announcement{
	>.inner{
		position: static;
		>h3{
			font-size: 1.3rem;
		}

		>p{
			font-size: 1.2rem;
			strong{
				font-weight: normal;
				font-style: italic;
			}

			>a{
				text-decoration: underline;

				&.button{
					background: #333;
				}
			}
		}
	}
}
But that would be both unnecessary and hard to maintain. It would also limit the control and flexibility of the content within. A better approach would be to reduce specificity;

.announcement{
	.inner{
		position: static;
	}
	
	h3{
		font-size: 1.3rem;
	}

	p{
		font-size: 1.2rem;
	}

	strong{
		font-weight: normal;
		font-style: italic;
	}

	a{
		text-decoration: underline;

		&.button{
			background: #333;
		}
	}
}
This is flexible, maintainable, and the result is the same. Now, there may be a reason to have a declaration just for when strong is inside the paragraph tag p strong, but unless there specifically is, it shouldn't be specified.

JS
Javascript should be linked to your HTML files in it's uncompiled format during dev. There is no reason to use compiled JS, and waiting 2-5 seconds depending on liobrary size and complexity before you can view a change each time you edit a file is a significant inconvenice. Therefor JS can most typically be recompiled when you need to test the compilation process.

JS Structure
The base Library is jQuery simply because we use WordPress in the majority of our projects. Additional libraries should be added to the end of the HTML document right before jQuery but before the script.js file.

The script.js file will house most (all) of your custom JS. If your custom scripting grows beyond the reasonable scope of a single file, break it down logically as to it's use, and include it after 3rd party libraries but before the script.js file.

Images
Images should be places in /src/img/ but your css should reference them from /assets/ as that is where the compressed and optimized images reside (as well as all other compiled / transpiled assets).

Gulp Usage
Gulp is used to compile and minify either the Less or SCSS (as you prefer), as well as the JS in the project. It also compresses and moves images from the /src/img/ folder to /assets/.

The gulp commands available to you are as follows: gulp - the default gulp command will watch & compile your JS and SCSS.

gulp watchScss - will watch & compile your SCSS.

gulp watchAllScss - will watch & compile your JS, SCSS, and minify your images.

gulp watchLess - will watch & compile your Less.

gulp watchAllLess - will watch & compile your JS, Less, and minify your images.

gulp watchJs - will watch & compile your JS.

gulp watchImg - will watch & minify your images.

gulp scssTask - will compress your SCSS and quit.

gulp lessTask - will compress your Less and quit.

gulp jsTask - will compress your JS and quit.

gulp imgTask - will minify your images.

Installation
After cloning the repository, install the dependencies by running the following command in the /tools/ directory:

npm install
This assumes you have node; get it here https://docs.npmjs.com/downloading-and-installing-node-js-and-npm

That's it, you're now ready to run Gulp commands. Don't forget, gulp commands should always be run from within the /tools/ directory else they'll fail.

Viewing HTML files
While you could open the html files directly, I wouldn't recommend it. You can use a VSCode extension such as GoLive!, or the tried and true http-server available on NPM, or however you wish to server them. The easiest is probably http-server.

To get started with it:

npm install http-server -g
and to run a server from the current directory (NOT /tools/ but the root where this readme lives)

http-server
In a short moment you'll have a server available to view your files. Read more about the configuration options of http-server on the package page https://docs.npmjs.com/downloading-and-installing-node-js-and-npm
