# larvia - heart - package
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

## step 1.2 add package to composer 2 (only for local development)
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
git clone git@github.com:laravia/posting.git packages/laravia/posting
</pre>

## 1.5 | clone a existing packages (example heart) (only for local development)
<pre>
sail art laravia:package:clone packages/laravia/heart counter lookup
</pre>

## 1.6 | copy from tmp to packages
<pre>
cp -r storage/framework/tmp/packages/laravia/counter packages/laravia/
</pre>

## 1.8 | run composer
<pre>
sail composer update
</pre>

## 1.9 | happy coding
