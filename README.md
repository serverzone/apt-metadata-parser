# Apt metadata parser

## Installation

### Composer

Download this package using composer:

```bash
composer require serverzone/apt-metadata-parser
```

## Usage

### How to read content

#### Local file

```php
$content = file_get_contents('Packages');
```

#### Local gziped file

```php
$content = file_get_contents('compress.zlib://Packages.gz');
```

#### Remote gziped file

```php
$content = file_get_contents('compress.zlib://http://ftp.debian.org/debian/dists/stretch-backports/main/binary-amd64/Packages.gz');
```

### How to parse content

```php
  $parser = new Parser($content);
  $package = $parser->getPackage('claws-mail');
  $description = $package[0]->getTag('Description');
```
