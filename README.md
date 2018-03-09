# Laraparser
Parser based on Laravel.

###Simple for use, simple for customize

## Install
* Clone the repository
* Copy .env.example file to .env inside project and fill the database information.
* Create a database locally named by .env
* Open the console and cd your project root directory
* ``$ composer install``
* ``$ php artisan migrate``

## Run
_use ``php artisan`` or ``art``_

* Get html-pages to database
```
$ art laraparser:getpageshtml
``` 
* Parse html-pages in dabase and get url's for items
```
$ art laraparser:getitems
```
* Get items html-pages to database
```
$ art laraparser:getitemshtml
```
* Parse data from items html-pages
```
$ art laraparser:getitemsdata
```
* Put items data to csv-file (separator ``\t``). Filename data.csv
```
$ art laraparser:getcsv
```
* Clear database
```
$ art laraparser:clear
```

## Develop

* Create class of your parser ``app/Parsers``
* Your class must 
```php
class YourParser extends Parser implements ParserInterface {}
```
* Bind your parser ``app/Providers/AppServiceProvider.php``
```php
    $this->app->bind(ParserInterface::class, YourParser::class);
```
* Use selectors simplehtmldom ```http://simplehtmldom.sourceforge.net/manual.htm```

### Functions
* ``fields`` - array of name's fields
* ``pages`` - array of pages url's
* ``findItemsOnPage`` - find items url's on page (simplehtmldom)
* ``customUpdateData`` - modify all data before save to database
* ``customUpdateDataCsv`` - modify all data before save to csv-file
* ``[fieldname]DataFind`` - find field data on item html-page (simplehtmldom)
* ``[fieldname]CsvCallback`` - modify field data before save to csv-file
* ``[fieldname]DataChange`` - modify field data before save to dabase


## Idies
* Custom filename and separator for csv-file
* 
* Web-interface
* Multithreaded parsing with proxy
* Test mode
* Set of functions for parse data
