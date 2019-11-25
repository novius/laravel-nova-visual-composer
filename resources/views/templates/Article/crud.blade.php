<div class="shadow-md mb-4 w-full">
    <div class="px-6 py-4">
        <div class="font-bold text-xl mb-4">
            {{ $templateDetails['name_trans'] }}
        </div>
        <div class="form-group mb-2 w-1/2">
            <label class="block text-grey-darker text-sm font-bold mb-2" for="username">
                {{ trans('nova-visual-composer::templates.'.$templateName.'.crud_title') }}
            </label>
            <input class="js-visual-field w-full form-control form-input form-input-bordered"
                   type="text"/>
        </div>
        <div class="form-group mb-2 w-1/2">
            <label class="block text-grey-darker text-sm font-bold mb-2" for="username">
                {{ trans('nova-visual-composer::templates.'.$templateName.'.crud_subtitle') }}
            </label>
            <input class="js-visual-field w-full form-control form-input form-input-bordered"
                   type="text"/>
        </div>
        <div class="form-group mb-2">
            <label class="block text-grey-darker text-sm font-bold mb-2" for="username">
                {{ trans('nova-visual-composer::templates.'.$templateName.'.crud_text') }}
            </label>
            <textarea name="content"
                      class="js-visual-field js-wysiwyg w-full form-control form-input-bordered py-2 h-20"></textarea>
        </div>
    </div>
</div>
