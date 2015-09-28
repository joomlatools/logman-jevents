LOGman JEvents plugin
========================

Plugin for integrating [JEvents](https://www.jevents.net/) with LOGman. [LOGman](http://joomlatools.com/logman) is a user analytics and audit trail solution for Joomla.

## Installation

### Composer

You can install this package using [Composer](https://getcomposer.org/). Create a `composer.json` file inside the root directory of your Joomla! site containing the following code:

```
{
    "require": {        
        "joomlatools/plg_logman_jevents": "dev-master"
    },
    "minimum-stability": "dev"
}
```

Run composer install.

### Package

For downloading an installable package just make use of the **Download ZIP** button located in the right sidebar of this page.

After downloading the package, you may install this plugin using the Joomla! extension manager.

## Usage

After the package is installed, make sure to enable the plugin and that both LOGman and JEvents are installed.

## Supported activities

The following JEvents actions are currently logged:

### Events

* Add
* Edit
* Publish/Unpublish/Trash

### Categories

* Add
* Edit
* Publish/Unpublish/Archive/Trash
* Delete

### Event Repetitions

* Edit

## Limitations

Delete actions on Events and their repetitions are not supported due to insufficient data being passed to the dispatcher. Only the resource ID is passed and there is no way of grabbing the required data before the delete action takes place, i.e. no before delete events.