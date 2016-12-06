README
======

![license](https://img.shields.io/packagist/l/bafs/via.svg?style=flat-square)
![PHP 5.5+](https://img.shields.io/badge/PHP-5.5+-brightgreen.svg?style=flat-square)

What is Mensam?
-----------------

Mensam is a PHP Grid manager. Support for:
- multiple templates like Bootstrap 3.
- sortable columns.
- multiple data storage.
- pagination.
s
Installation
------------

The best way to install is to use the composer by command:

composer require newclass/mensam

composer install

Use example
------------
    
	use Mensam\Column;
	use Mensam\Formatter\Bootstrap3Formatter;
	use Mensam\GridBuilder;
	use Mensam\GridDataManager;
	use Mensam\Request;

    $grid = new GridBuilder();
    $request='...';//set object implements interface Mensam\Request
    $dataManager='...';//set object implements interface Mensam\GridDataManager
    $grid->setRequest($request);
    $grid->addColumn(new Column('id', 'Id'));
    $grid->addColumn(new Column([
        'name',
        'subName'
    ], 'Name'));

    $grid->addColumn(new Column([
        'subName'
    ], 'Sub name'));

    $grid->addColumn(new Column([
        'id'
    ], 'No sort',null,[]));

    $grid->setDataManager($dataManager);
    $grid->setFormatter(new Bootstrap3Formatter());
    echo $grid->render(); //return html template