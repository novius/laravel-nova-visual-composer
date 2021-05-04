<div class="shadow-md mb-4 w-full">
    <div class="px-6 py-4">
        <div class="font-bold text-xl mb-4">
            {{ $templateDetails['name_trans'] }}
        </div>
        <div class="form-group mb-2 w-1/2">
            <label class="block text-grey-darker text-sm font-bold mb-2">
                {{ trans('nova-visual-composer::templates.'.$templateName.'.crud_title') }}
            </label>
            <input class="js-visual-field w-full form-control form-input form-input-bordered"
                   data-field-name="title"
                   type="text"/>
        </div>
        <div class="form-group mb-2 w-1/2">
            <label class="block text-grey-darker text-sm font-bold mb-2">
                {{ trans('nova-visual-composer::templates.'.$templateName.'.crud_subtitle') }}
            </label>
            <input class="js-visual-field w-full form-control form-input form-input-bordered"
                   data-field-name="subtitle"
                   type="text"/>
        </div>
        <div class="form-group mb-2">
            <label class="block text-grey-darker text-sm font-bold mb-2">
                {{ trans('nova-visual-composer::templates.'.$templateName.'.crud_text') }}
            </label>
            <textarea name="content"
                      data-field-name="content"
                      class="js-visual-field js-wysiwyg w-full form-control form-input-bordered py-2 h-20"></textarea>
        </div>
        <div class="form-group mb-2">
            <label class="block text-grey-darker text-sm font-bold mb-2">
                {{ trans('nova-visual-composer::templates.'.$templateName.'.image') }}
            </label>
            <div class="form-group w-1/2">
                <input type="file"
                       class="filepond js-visual-field js-image-uploader"
                       data-field-name="image" />
            </div>
        </div>
        <div class="form-group mb-2 w-1/2">
            <label class="block text-grey-darker text-sm font-bold mb-2">
                {{ trans('nova-visual-composer::templates.'.$templateName.'.image_alt') }}
            </label>
            <input class="js-visual-field w-full form-control form-input form-input-bordered"
                   data-field-name="image_alt"
                   type="text"/>
        </div>
    </div>
</div>
