# Disc Wolf
An open source "Wolf" game-mode tracker for up to 9 players.

# Development In Progress

## LOOSE ROADMAP

| STATUS    | GOAL                          | NOTE |
| :-------: | :---------------------------- | :--- |
| &#9745;   | Determine Application Goal    | |
| &#9745;   | Choose Design Pattern         | MVC |
| &#9745;   | Establish project scaffolding | |
| &#9745;   | Establish server environment  | Docker |
| &#9745;   | Set up unit testing.          | PHPUnit |
| &#9744;   |  |  |
| &#9744;   |  |  |
| &#9744;   |  |  |

## Getting Started

To work on this stack, first bring it up with:

```bash
$ docker-compose up -d
```

Then, install the dependencies with:

```bash
$ docker exec -it discwolf_app composer install
```

and look to localhost in your browser.

### NOTE
As it is intended to be a mobile app in the end, test your views on
your mobile device by going to your server's IP address.

## Packages of Note

### Collections (Laravel: illuminate/support)

This package is the bees knees when it comes to manipulating sets of
nested arrays (RDBs, and json objects). It's from the laravel team, but
I learned how to pull in just this one library. Here's a list of all the
methods you can call on it:  

https://laravel.com/docs/7.x/collections#available-methods

### Unit Testing (PHPUnit: phpunit/phpunit)

A tests folder has been added that contains unit tests. The `tests/` 
directory is namespaced in the composer file, and structured to reflect 
the `app/` directory. Each class in the `app/` directory has a class, and 
each class has a test class with the same name and the Test suffix. Ex:
`./app/Models/Game.php and ./tests/Models/GameTest.php`.

#### So, what do we test?
Ideally, everything, but that would not be real-world. There's a ton of 
smart writing on testing philosophies, so we'll just pick a reasonable one
for this specific project: "test object interfaces only."  

Abstractly, that means, we only test instantiation of, and access 
to/through the IO points of our objects. Specifically, that means don't 
bother testing private methods/attributes/properties. The concept being, 
if something in a private method is messed up in a meaningful way, it will
show up as an error in the interface that ultimately relies on that method.

#### Running The Tests

```bash
$ docker exec -it discwolf_app vendor/bin/phpunit tests/
```