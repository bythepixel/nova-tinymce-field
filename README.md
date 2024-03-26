# nova-tinymce-field
This package is a Nova WYSIWIG Field that uses TinyMCE, which has an extensive set of configuration/plugins.

- [Installation](#installation)
- [Local Development](#local-development)
    - [Creating the submodule](#creating-the-submodule)
    - [Updating composer.json](#updating-composerjson)
    - [Updating the Vue components](#updating-the-vue-components)
- [TinyMCE](#tinymce)
    - [Configuration](#configuration)
    - [Shortcodes](#shortcodes)
    - [Enabling the shortcode plugin](#enabling-the-shortcode-plugin)
    - [Registering a shortcode](#registering-a-shortcode)

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

### TinyMCE

#### Configuration
See https://www.tiny.cloud/docs/configure/ for configuration details about the core TinyMCE package. This custom field uses a Vue wrapper to inject the field; documentation for which [can be found here](https://www.tiny.cloud/docs/integrations/vue/).

#### Shortcodes
This TinyMCE implementation includes a custom plugin to streamline shortcode insertion inside the editor. A caveat, as of writing, opening/closing shortcode tags are _not_ supported.

#### Enabling the shortcode plugin
To enable the shortcode plugin, which adds a new "Shortcodes" button to the WYSIWYG toolbar, you must update `plugins` and `toolbar` in the `nova-tinymce-field`:

```
'plugins' => [
    'plugin1 plugin2 shortcodes'
],
'toolbar' => [
    'button1 button2 | button3 button4 | shortcodes',
]
```

#### Registering a shortcode
Our primary driver for shortcodes on Laravel projects is `webwizo/laravel-shortcodes`, which uses the following syntax for shortcode snippets:

```
[shortcode-name attribute="value" another_attribute="value"]
```

The easiest way to prepare a shortcode class for registration in the `nova-tinymce-field` config is to use the `HasWysiwygShortcode` trait included in this package. There are three `public static` properties you need to include in the class to ensure the trait's helper methods can correctly build the required TinyMCE panels: 

- `$name` - The display name for the shortcode
- `$slug` - The slug used for the shortcode snippet
- `$attributes` - A non-associative array of attributes used by the shortcode

An example class would look like this:

```
use Bythepixel\NovaTinymceField\Traits\HasWysiwygShortcode;

class AccentedHeadingShortcode
{
    use HasWysiwygShortcode;

    public static string $name = "Accented Heading";
    public static string $slug = "accented-heading";
    public static array $attributes = [
        'heading',
        'subheading'
    ];

    // required for webwizo/laravel-shortcodes registration
    public function register() {}
}
```

Once the class is ready to register into the plugin, add it to the `nova-tinymce-field` config's `shortcodes` array:

```
use App\Shortcodes\AccentedHeadingShortcode;

return [
    ... other config options,
    'shortcodes' => [
        AccentedHeadingShortcode::class
    ]
]
```

You may also have to clear Laravel's config cache with `artisan config:clear`.

Once the config has been properly updated, you should be able to see a tab for it after clicking the `Shortcodes` button in the WYSIWYG toolbar!