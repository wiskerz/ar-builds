# Atlas Reactor Builds

Generate the Atlas Reactor build site by parsing entries from wikipedia

## Updating

To re-cache and update the information from the wikipedia

```
make cache
```

This also fetches new information. 
Freelancer information is found in [lancers.php](lancers.php) as key-value pairs.
The key is associated with the lancer internal ID used for mapping files, and the value is the name of the lancer page on the wiki.

## Rebuilding the website

After caching issue make to build the [index.html](index.html) which contains all information.

```
make
```

## Mapping

For each freelancer make sure to include its key-value pair in [lancers.php](lancers.php).
The key is used to associate images with the ability and picks.
Typically, abilities are found under [site/content/Abilities](site/content/Abilities).
A lancer folder is named using its key, and ability images are numbered 1-5.
For the freelancer icon, check [site/content/FL](site/content/FL), the lancer portrait will be under `FLpick_key.png`.

## Utility Scripts

The utility [scripts](scripts/) path is used to simply convert FSK elements.  
