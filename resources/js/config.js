/**
 * Config file of laravel nova visual composer package
 * You can override it with laravel publish command (documented in README.md)
 */
window.laraNovaVisualComposerConfig = {
  quill: {
    toolbarOptions: [
      ['bold', 'italic', 'underline', 'strike'],
      [{'list': 'ordered'}, {'list': 'bullet'}],
      [{'script': 'sub'}, {'script': 'super'}],
      [{'indent': '-1'}, {'indent': '+1'}],
      [{'header': [1, 2, 3, 4, 5, 6, false]}],
      [{'color': []}, {'background': []}],
      [{'font': []}],
      [{'align': []}],
      ['clean'],
      ['image', 'video', 'link', 'html'],
    ],
  },
};
