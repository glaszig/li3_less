# li3_less

This is a real-time less parser for your li3 app.

## Usage

Put your less stylesheets into a folder 'less' in your webroot. For example:  

	app/webroot/less/styles.css.less

In your layout view file, link to your stylesheet as usual:  

	echo $this->Html->style('styles.css');

From now on, magic will happen, as long as you keep your .css.less files in `webroot/less`.
li3_less will now convert your `styles.css.less` file into `styles.css` and serve it.
The `.css` file will be cached and served in `webroot/less` until you modify the corresponding less file.

## Installation

Add a submodule to your li3 libraries:

	git submodule add git@github.com:glaszig/li3_less.git libraries/li3_less

and activate it in you app (config/bootstrap/libraries.php), of course:

	Libraries::add('li3_less');

Also, you should chmod the less folder, so generated .css files can be cached.

	chmod 0777 app/webroot/less

## Requirements

- [lithium li3](https://github.com/UnionOfRAD/lithium)

## Todos

- use Libraries:add() instead of require to load lessphp
- move the less folder out of the webroot

### Credits

- [lessphp](http://leafo.net/lessphp/)
- [li3](http://www.lithify.me)
- [bruensicke's fork](http://github.com/bruensicke/li3_less)

Please report any bug at [glaszig/li3_less/issues](https://github.com/glaszig/li3_less/issues) 
or at [bruensicke/li3_less/issues](https://github.com/bruensicke/li3_less).

