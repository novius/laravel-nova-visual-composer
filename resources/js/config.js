/**
 * Config file of laravel nova visual composer package
 * You can override it with laravel publish command (documented in README.md)
 */

// Register custom text size tool
window.laraNovaVisualComposerSize = ['14px', '16px', '18px', '21px'];

window.laraNovaVisualComposerConfig = {
  quill: {
    toolbarOptions: [
      ['bold', 'italic', 'underline', 'strike'],
      [{ list: 'ordered' }, { list: 'bullet' }],
      [{ script: 'sub' }, { script: 'super' }],
      ['blockquote', 'code-block'],
      [{ indent: '-1' }, { indent: '+1' }],
      [{ header: [1, 2, 3, 4, 5, 6, false] }],
      [{ color: [] }, { background: [] }],
      [{ size: window.laraNovaVisualComposerSize || ['14px', '16px', '18px', '21px'] }],
      [{ font: [] }],
      [{ align: [] }],
      ['clean'],
      ['image', 'video', 'link', 'html'],
    ],
  },
};
