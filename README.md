# nova-tinymce-field
This package is a Nova WYSIWIG Field that uses TinyMCE, which has an extensive set of configuration/plugins.

### Installation
- `composer require bythepixel/nova-tinymce-field`
- `php artisan vendor:publish --provider="Bythepixel\\NovaTinymceField\\FieldServiceProvider"`

### Local development
Developing this package on your local environment might be tricky, especially if using Laravel Sail to containerize the app. Ordinarily when developing a local package, you'd be able to create a symlink to an external directory, and reference that symlink in the `composer.json` file. However, that's a no-go with Sail, since the containers won't have access to that symlinked directory.

To be able to work on the package, see the changes reflected during development, while also isolating this repo while inside your project's repo, we use Git submodules and update the project's `composer.json` to point to the local directory.


#### Creating the submodule
In the root of your project, run `git submodule add https://github.com/bythepixel/nova-tinymce-field`. This is effectively cloning this repo into your project, while also creating a new `.gitmodules` file, which should look like this:

```
[submodule "nova-tinymce-field"]
    path = nova-tinymce-field
    url = https://github.com/bythepixel/nova-tinymce-field
```

If necessary, this file can be deleted once development is complete. This effectively notifies git to keep this repo, and your project repo, separated. You may, however, want to update your project's root `.gitignore` to exclude this directory. Individual files within the submodule won't be tracked, but it will track the new directory.

#### Updating composer.json
Once the submodule has been created, you can update your project's `composer.json` to point to the new local directory.

First, you must create a new entry under `repositories`:

```
"repositories": [
    ...existing repositories,
    {
        "type": "path",
        "url": "./nova-tinymce-field"
    }
]
```

Second, you must update the `bythepixel/nova-tinymce-field` entry under `required`. If you're developing on this repo's `master` branch, pass in `@dev`.

```
"bythepixel/nova-tinymce-field": "@dev",
```

If you're working on a different branch, the declaration should look like `dev-NAME-OF-BRANCH`:

```
"bythepixel/nova-tinymce-field": "dev-hotfix-field",
```

If your project already has a `composer.lock` file that includes this package, run `composer update bythepixel/nova-tinymce-field`, otherwise just run `composer i`.

Once local development is complete, and your changes have been pushed up to `origin`, you can remove the `.gitmodules` file, and remove the `nova-tinymce-field` directory from your project. Should you choose to commit the `.gitmodules` file to the repo, all future `git clone`s on your project will include empty folders for each declared submodule. Each folder can be populated by `cd`ing into the folder, and running two commands:
1) `git submodule init`
2) `git submodule update`

For more info on submodules, feel free to [read the official documentation](https://git-scm.com/book/en/v2/Git-Tools-Submodules).

#### Updating the Vue components

Inside the `nova-tinymce-field` directory, run `npm i` and `npm run nova:install` to install the field dependencies. For live updates, run `npm run watch`.

Once development is complete, be sure to `npm run prod` to rebuild the assets.

### TinyMCE Configuration
See: https://www.tiny.cloud/docs/configure/
