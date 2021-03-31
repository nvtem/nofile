(function() {
    let totalUploadSize = 0;

    function formatBytes(bytes, decimals) {
        if (bytes === 0) return '0 B';

        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];

        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    function goToUploadStep1() {
        totalUploadSize = 0;
        $('.upload-step-2').hide();
        $('.upload-step-1').show();
        $('#file-upload-form .file-list').empty();
    }

    $('#file-upload-form .choose-file-btn').click(function () {
        $('#file-upload-form .file-input').click();
    });

    $('#file-upload-form .file-input').on('change', function () {
        $('#file-upload-form .upload-step-1').hide();
        $('#file-upload-form .upload-step-2').show();

        let files = $('#file-upload-form .file-input')[0].files;

        for (let i = 0; i < files.length; i++) {
            let file = files[i];
            let tr = $("<tr>").appendTo('.file-list');
            $("<td class='icon-and-name'><span>" + file.name + "</span></td>").appendTo(tr);
            $("<td class='size-and-dl-link w-100px'><span>" + formatBytes(file.size, 2) + "</span></td>").appendTo(tr);
            totalUploadSize += file.size;
        }

        $("<tr><td colspan='3' class='total-size'>Total: " + formatBytes(totalUploadSize, 2) + "</td></tr>").appendTo('.file-list');
    });

    $('#file-upload-form .clear-file-list-btn').click(function () {
        goToUploadStep1();
    });

    $('#file-upload-form .field-set_password input[type=checkbox]').change(function () {
        if ($('#set_password').prop('checked'))
            $('#file-upload-form .field-password').show();
        else
            $('#file-upload-form .field-password').hide();
    });

    $('.delete-fileset-link').click(function () {
        if (confirm('Delete fileset?'))
            window.location = this.attr('href');
        else
            return false;
    });

    $('.show-about-box-btn').click(function () {
        let elem = $('.about-box');
        if (window.innerWidth > 530) {
            elem.css('top', $(this).position().top + 20);
            elem.css('left', $(this).position().left);
        }
        elem.show();
        $('.about-box-overlay').show();
        return false;
    });

    $('.about-box-overlay, .about-box .close-btn').click(function (e) {
        $('.about-box').hide();
        $('.about-box-overlay').hide();
        return false;
    });

    $('.files-uploaded-notice .close-btn').click(function(){
        $('.files-uploaded-notice').hide();
        return false;
    });

    $('#file-upload-form').ajaxForm({
        beforeSubmit: function () {
            if ($('#file-upload-form .field-set_password input[type=checkbox]').prop('checked')) {
                if ($('#file-upload-form .password-input').val() === '') {
                    alert('Enter password!');
                    return false;
                }
            }

            if (totalUploadSize > fileSetMaxSize) {
                alert('Max upload size is ' + formatBytes(fileSetMaxSize, 0));
                return false;
            }

            $('#file-upload-form .clear-file-list-btn').hide();
            $('#file-upload-form .field-set_password').hide();
            $('#file-upload-form .field-password').hide();
            $('#file-upload-form .submit-btn').attr('disabled', 'disabled');

            $('#file-upload-form .upload-progress-bar').show();
        },

        uploadProgress: function (event, position, total, percentComplete) {
            $('#file-upload-form .upload-progress-bar .filler').width(percentComplete + "%");
        },

        complete: function (xhr) {
            let result = xhr.responseText.split(':');

            if (result[0] === 'OK') {
                window.location = result[1];
            } else {
                alert('Error!');
                location.reload();
            }
        },
    });

    if ("allowFileDrop" in window && allowFileDrop === true) {
        $(window).on('dragenter', function () {
            $('.drop-file-zone').show();
        });

        $('.drop-file-zone')
            .on('dragover', function (e) {
                e.preventDefault();
                e.stopPropagation();
            })

            .on('dragleave', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $('.drop-file-zone').hide();
            })

            .on('drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $('.drop-file-zone').hide();
                if ($('#file-upload-form .file-list').innerHTML !== '')
                    goToUploadStep1();
                $('.file-input')[0].files = e.originalEvent.dataTransfer.files;
                $('.file-input').trigger('change');
            });
    }
}());
