LOGman JEvents plugin
========================

Plugin for integrating [JEvents](https://www.jevents.net/) with LOGman. [LOGman](http://joomlatools.com/logman) is a user analytics and audit trail solution for Joomla.

## Installation

### Composer

You can install this package using [Composer](https://getcomposer.org/) by simply going to the root directory of your Joomla site using the command line and executing the following command:

```
composer require joomlatools/logman-jevents:*
```

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

At the moment and on the latest stable version of JEvents (v3.4.0RC6), delete actions on Events and their repetitions are not supported due to insufficient data being when triggering the event.