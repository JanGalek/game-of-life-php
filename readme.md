Nette Web Project
=================

This is a simple, skeleton application using the [Nette](https://nette.org). This is meant to
be used as a starting point for your new projects.

[Nette](https://nette.org) is a popular tool for PHP web development.
It is designed to be the most usable and friendliest as possible. It focuses
on security and performance and is definitely one of the safest PHP frameworks.

If you like Nette, **[please make a donation now](https://nette.org/donate)**. Thank you!


Requirements
------------

- Web Project for Nette 3.1 requires PHP 8.0


Installation
------------

The best way to install Web Project is using Composer. If you don't have Composer yet,
download it following [the instructions](https://doc.nette.org/composer). Then use command:

	composer create-project nette/web-project path/to/install
	cd path/to/install


Make directories `temp/` and `log/` writable.


Web Server Setup
----------------

The simplest way to get started is to start the built-in PHP server in the root directory of your project:

	php -S localhost:8000 -t www

Then visit `http://localhost:8000` in your browser to see the welcome page.

For Apache or Nginx, setup a virtual host to point to the `www/` directory of the project and you
should be ready to go.

**It is CRITICAL that whole `app/`, `config/`, `log/` and `temp/` directories are not accessible directly
via a web browser. See [security warning](https://nette.org/security-warning).**



Quest
-----
Game of Life
Consider a representation of a "world" as an n by n matrix. Each element in the matrix may contain 1 organism. Each organism lives, dies and reproduces according to the following set of rules:

If there are two or three organisms of the same type living in the elements surrounding an organism of the same, type then it may survive.

If there are less than two organisms of one type surrounding one of the same type then it will die due to isolation.

If there are four or more organisms of one type surrounding one of the same type then it will die due to overcrowding.

If there are exactly three organisms of one type surrounding one element, they may give birth into that cell. The new organism is the
same type as its parents. If this condition is true for more than one
species on the same element then species type for the new element is chosen randomly.

If two organisms occupy one element, one of them must die (chosen randomly) (only to resolve initial conflicts).

The "world" and initial distribution of organisms within it is defined by an XML file of the following format:
````
<?xml version="1.0" encoding="UTF-8"?>
<life>

<world>
<dimension>n</dimension > <!-- Dimension of the square "world" -->
<speciesCount>m</speciesCount> <!-- Number of distinct species -->
<iterationsCount>4000000</iterationsCount> <!-- Number of iterations to be calculated >>
</world>

<organisms>
<organism>
<x_pos>x</x_pos> <!-- x position -->
<y_pos>y</y_pos> <!-- y position -->
<species>type</species> <!-- Species type -->
<organism>
</organisms>

</life>
````

After iterations, the state of the "world" is to be saved in an XML file, out.xml, of the same format as the initial definition file. Alternatively, you can use JSON file format instead.
Please, include at least one unit test, that will cover any of the business logic.
