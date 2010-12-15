# li3_less

This is a real-time less parser for your li3 setup.

## Requirements

li3: <http://www.lithify.me>

li3_less (see installation notes below)

Put your less stylesheets into a folder 'less' in your webroot. For example:  
/path/to/li3/app/webroot/less/my-styles.css.less

In your layout view file, link to your stylesheet as usual:  
> echo $this->Html->style('my-styles.css');

From now on, magic will happen, as long as you keep your .css.less files in webroot/less.  
li3_less will now convert your my-styles.css.less file into my-styles.css and serve it.  
The .css file will be cached and served in webroot/less until you modify the corresponding less file.

## Installation

Clone li3_less with your favorite git client into the libraries folder of your li3 app.

> git clone git@github.com:glaszig/li3_less.git /path/to/li3/libraries/li3_less

### Credits

lessphp: <http://leafo.net/lessphp/>  
li3: <http://www.lithify.me>

Please report any bug.