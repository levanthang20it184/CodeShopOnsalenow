tinymce.init({
    mode: "exact",
    selector: '#page-pagecontent,#updatepage-pagecontent,#emailmessage,#tblfaq-faq_description,#description',
    //editor_selector: 'mceAdvanced',
    plugins: [
        "advlist autolink autosave link image media lists charmap print preview hr anchor pagebreak spellchecker",
        "searchreplace wordcount visualblocks visualchars code fullscreen nonbreaking",
        "table contextmenu directionality emoticons template textcolor paste fullpage textcolor image filemanager"
    ],
    setup: function (editor) {
        editor.on('change', function () {
            tinymce.triggerSave();
        });
    },


    toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect",
    toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor  image filemanager media code | inserttime preview | forecolor backcolor",
    toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking pagebreak restoredraft",
    menubar: false,
    toolbar_items_size: 'small',

    style_formats: [
        {title: 'Bold text', inline: 'b'},
        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
        {title: 'Example 1', inline: 'span', classes: 'example1'},
        {title: 'Example 2', inline: 'span', classes: 'example2'},
        {title: 'Table styles'},
        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}


    ],
    width: 720,
    height: 330
});


