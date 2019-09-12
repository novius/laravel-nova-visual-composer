# Visual Composer for Laravel Nova
[![Travis](https://img.shields.io/travis/novius/laravel-nova-visual-composer.svg?maxAge=1800&style=flat-square)](https://travis-ci.org/novius/laravel-nova-visual-composer)
[![Packagist Release](https://img.shields.io/packagist/v/novius/laravel-nova-visual-composer.svg?maxAge=1800&style=flat-square)](https://packagist.org/packages/novius/laravel-nova-visual-composer)
[![Licence](https://img.shields.io/packagist/l/novius/laravel-nova-visual-composer.svg?maxAge=1800&style=flat-square)](https://github.com/novius/laravel-nova-visual-composer#licence)

> **WARNING**: this package is currently in development.

## Requirements

* PHP >= 7.1.3
* Laravel Framework >= 5.8
* Nova >= 2.0

## Installation

```sh
composer require novius/laravel-nova-visual-composer:dev-master
```

### Configuration

Some options that you can override are available.

```sh
php artisan vendor:publish --provider="Novius\NovaVisualComposer\NovaVisualComposerServiceProvider" --tag="config"
```

## Purge TMP uploaded files

In your app/Console/Kernel.php file, you should register a daily job to purge any stale files :

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('nova-visual-composer:purge-tmp-files')
        ->daily();
}
```

By default tmp file is stale considered after 24h. You can override this value in configuration file with `seconds_before_purge_tmp_files` key.

## Usage

**Step 1**

Configure your model by adding `object` to the desired column.

```php
use Illuminate\Database\Eloquent\Model;

class Foo extends Model {
    protected $casts = [
        'content' => 'object',
    ];
}
```

**Step 2**

Add the field to your Nova resource.

```php
use App\Nova\Resource;
use Novius\NovaVisualComposer\NovaVisualComposer;

class FooResource extends Resource
{       
    public function fields(Request $request)
    {
        return [
            // ..
            NovaVisualComposer::make('Content')
                                ->stacked(),
            // ..
        ];
    }
}

```

## Create new row templates

**Step 1**

You have to publish lang and view files.

```bash
php artisan vendor:publish --provider="Novius\NovaVisualComposer\NovaVisualComposerServiceProvider" --tag="config"
php artisan vendor:publish --provider="Novius\NovaVisualComposer\NovaVisualComposerServiceProvider" --tag="views"
```

**Step 2**

Create your own Row Template and link it into configuration file.

*Row Template Class*

```php
namespace App\Nova\Templates\Rows;

use Novius\NovaVisualComposer\Templates\RowTemplateAbstract;
use Novius\NovaVisualComposer\Traits\HasImageField;
use Novius\NovaVisualComposer\Traits\HasPrunableFiles;

class ImageText extends RowTemplateAbstract
{
    use HasImageField;
    use HasPrunableFiles;

    public static $name = 'image-text';

    protected static function imageFieldsIndexes(): array
    {
        return [0]; // Because image field is the first field that contains "js-visual-field" class of CRUD view
    }
}
```

*Add it to configuration file*

```php
return [

    ...

    'templates' => [
        \Novius\NovaVisualComposer\Templates\Article::class,
        \Novius\NovaVisualComposer\Templates\ImageMultiple::class,
        \Novius\NovaVisualComposer\Templates\ImageSimple::class,
        \Novius\NovaVisualComposer\Templates\Separator::class,
        \Novius\NovaVisualComposer\Templates\Title::class,

        // Custom Template
        \App\Nova\Templates\Rows\ImageText::class,
    ],
];
```

**Step 3**

Create template views (CRUD + front views).

`resources/views/vendor/nova-visual-composer/templates/ImageText/crud.blade.php`


> TIPS :

* Each HTML field that you want to save must contains `js-visual-field` class.
* If you want a wysiwyg add `js-wysiwyg` class to textarea field.
* If you want an image upload field add `js-image-uploader` class to file field.
* If you want a multiple images upload field add `js-image-uploader` class + `multiple` attribute to file field.

```blade
<div class="shadow-md mb-4 w-full">
    <div class="px-6 py-4">
        <div class="font-bold text-xl mb-4">
            {{ $templateDetails['name_trans'] }}
        </div>
        <div class="flex">
            <div class="w-1/3 pr-3">
                <div class="form-group">
                    <input type="file" class="filepond js-visual-field js-image-uploader">
                </div>
            </div>
            <div class="w-2/3">
                <div class="form-group mb-2">
                    <label class="block text-grey-darker text-sm font-bold mb-2">
                        {{ trans('nova-visual-composer::templates.'.$templateName.'.crud_pre_title') }}
                    </label>
                    <input class="js-visual-field w-full form-control form-input form-input-bordered"
                           type="text"/>
                </div>
                <div class="form-group mb-2">
                    <label class="block text-grey-darker text-sm font-bold mb-2">
                        {{ trans('nova-visual-composer::templates.'.$templateName.'.crud_title') }}
                    </label>
                    <input class="js-visual-field w-full form-control form-input form-input-bordered"
                           type="text"/>
                </div>
                <div class="form-group mb-2">
                    <label class="block text-grey-darker text-sm font-bold mb-2">
                        {{ trans('nova-visual-composer::templates.'.$templateName.'.crud_text') }}
                    </label>
                    <textarea name="content"
                              class="js-visual-field js-wysiwyg w-full form-control form-input-bordered py-2 h-20"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

```

`resources/views/vendor/nova-visual-composer/templates/ImageText/front.blade.php`

```blade
@php
    if (empty($content) || !is_array($content)) {
            return;
    }

    list(
        $images,
        $preTitle,
        $title,
        $htmlContent,
    ) = $content;

    $image = is_array($images) ? array_shift($images) : null;
@endphp

<div class="block-image-text">
    @if (!empty($image))
        <img src="{{ asset('storage/'.$image) }}"/>
    @endif
    @if (!empty($preTitle))
        <h5 class="pre-title">
            {{ $preTitle }}
        </h5>
    @endif
    @if (!empty($title))
        <h3 class="title">
            {{ $title }}
        </h3>
    @endif
    @if (!empty($htmlContent))
        <div class="content">
            {!! $htmlContent !!}
        </div>
    @endif
</div>
```

**Step 4**

Add your translations to language files.

`resources/lang/vendor/nova-visual-composer/fr/templates.php`

```php
return [
    ...

    'image-text' => [
        'name' => 'Image / texte',
        'crud_pre_title' => 'Sur-titre',
        'crud_title' => 'Titre',
        'crud_text' => 'Texte',
    ],
];    
```

## Lint

Run php-cs with:

```sh
composer run-script lint
```

## Contributing

Contributions are welcome!
Leave an issue on Github, or create a Pull Request.


## Licence

This package is under [GNU Affero General Public License v3](http://www.gnu.org/licenses/agpl-3.0.html) or (at your option) any later version.
