# laravia - heart - package
Laravia Heart Plugin (the new Larvia Core)
This package is the new laravia core for all packages.
> needed orchid/platform

# create and install a new package (clone)
## step 1
add the new package to root/composer.json
<pre>
"laravia/counter": "dev-main",
</pre>

## step 1.1 add package to composer 1 (only for local development)
add package to root/composer.json
<pre>
"repositories": {
    "laravia/counter": {
        "laravia": "package",
        "name": "counter",
        "type": "path",
        "url": "packages/laravia/counter"
    },
}
</pre>

## step 1.2 add package to gitmodules 2 (only for local development)
add package to root/.gitmodules (bottom of file)
<pre>
[submodule "packages/laravia/counter"]
path = packages/laravia/counter
url =  git@github.com:laravia/counter.git
</pre>

## step 1.3 | create folder (only for local development)
<pre>
mkdir packages/laravia/counter
</pre>

## step 1.4 | create and clone your new repository (example: github)
<pre>
git clone git@github.com:laravia/counter.git packages/laravia/counter
</pre>

## 1.5 | clone an existing packages (example heart) (only for local development)
> package, search, replace, destination
<pre>
sail art laravia:package:clone packages/laravia/heart heart counter packages/laravia/counter
</pre>

## 1.6 | update if needed the package composer
<pre>
nano packages/laravia/counter/composer.json
</pre>

## 1.7 | run composer
<pre>
sail composer update
</pre>

## 1.8 | happy coding
