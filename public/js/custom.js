/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */
"use strict";

$(document).ready(function () {
    $('#form-add-category').on('submit', function (e) {
        e.preventDefault();
        const href = $(this).attr('action');
        $.ajax({
            type: $(this).attr('method'),
            url: href,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === false) {
                    $('#name').addClass('is-invalid');
                    $('#name_error').text(response.errors.name);
                } else if (response.status === true) {
                    $('#modal-add').modal('hide');
                    setTimeout(() => {
                        swal({
                            title: "Berhasil",
                            text: "Kategori ditambahkan!",
                            icon: "success",
                            timer: 1500,
                            buttons: false
                        });
                    }, 500);
                    setTimeout(() => {
                        window.location.href = href;
                    }, 2500);
                }
            }
        });
    });

    $('#modal-add').on('hidden.bs.modal', function () {
        $('#name').removeClass('is-invalid').val('');
        $('#name_error').text('');
    });

    $('.btn-delete').on('click', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        $('#id').val(id);
        $('#name-delete').text(name);
    });

    $('#form-delete').on('submit', function (e) {
        e.preventDefault();
        const href = $(this).attr('action');
        $.ajax({
            type: $(this).attr('method'),
            url: href,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === true) {
                    $('#modal-delete').modal('hide');
                    setTimeout(() => {
                        swal({
                            title: "Berhasil",
                            text: response.deleted + " dihapus!",
                            icon: "success",
                            timer: 1500,
                            buttons: false
                        });
                    }, 500);
                    setTimeout(() => {
                        window.location.href = href;
                    }, 2500);
                }
            }
        });
    });

    $('#modal-delete').on('hidden.bs.modal', function () {
        $('#id').val('');
        $('#name-delete').text('');
    });

    $('#form-add-item').on('submit', function (e) {
        e.preventDefault();
        const href = $(this).attr('action');
        $.ajax({
            type: $(this).attr('method'),
            url: href,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === false) {
                    (response.errors.name) ? $('#name').addClass('is-invalid') : $('#name').removeClass('is-invalid');
                    (response.errors.category) ? $('#category').addClass('is-invalid') : $('#category').removeClass('is-invalid');
                    (response.errors.stock) ? $('#stock').addClass('is-invalid') : $('#stock').removeClass('is-invalid');
                    $('#name_error').text(response.errors.name);
                    $('#category_error').text(response.errors.category);
                    $('#stock_error').text(response.errors.stock);
                } else if (response.status === true) {
                    $('#name').removeClass('is-invalid');
                    $('#category').removeClass('is-invalid');
                    $('#stock').removeClass('is-invalid');
                    $('#name_error').text('');
                    $('#category_error').text('');
                    $('#stock_error').text('');
                    setTimeout(() => {
                        swal({
                            title: "Berhasil",
                            text: "Barang ditambahkan!",
                            icon: "success",
                            timer: 1500,
                            buttons: false
                        });
                    }, 500);
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2500);
                }
            }
        });
    });

    $('.form-status').on('submit', function (e) {
        e.preventDefault();
        $(this).children('button').addClass('btn-progress');
        const href = $(this).attr('action');
        $.ajax({
            type: $(this).attr('method'),
            url: href,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === true) {
                    setTimeout(() => {
                        swal({
                            title: "Berhasil",
                            text: "Status diubah!",
                            icon: "success",
                            timer: 1500,
                            buttons: false
                        });
                    }, 500);
                    setTimeout(() => {
                        window.location.href = href;
                    }, 2500);
                }
            }
        });
    });

    $('#form-delete-item').on('submit', function (e) {
        e.preventDefault();
        const href = $(this).attr('action');
        $.ajax({
            type: $(this).attr('method'),
            url: href,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === true) {
                    $('#modal-delete').modal('hide');
                    setTimeout(() => {
                        swal({
                            title: "Berhasil",
                            text: "Barang dihapus!",
                            icon: "success",
                            timer: 1500,
                            buttons: false
                        });
                    }, 500);
                    setTimeout(() => {
                        window.location.href = href;
                    }, 2500);
                }
            }
        });
    });

    $('#form-create').on('submit', function (e) {
        e.preventDefault();
        const href = $(this).attr('action');
        $.ajax({
            type: $(this).attr('method'),
            url: href,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === false) {
                    (response.errors.name) ? $('#name').addClass('is-invalid') : $('#name').removeClass('is-invalid');
                    (response.errors.unique_code) ? $('#unique_code').addClass('is-invalid') : $('#unique_code').removeClass('is-invalid');
                    (response.errors.username) ? $('#username').addClass('is-invalid') : $('#username').removeClass('is-invalid');
                    (response.errors.password) ? $('#password').addClass('is-invalid') : $('#password').removeClass('is-invalid');
                    $('#name_error').text(response.errors.name);
                    $('#unique_code_error').text(response.errors.unique_code);
                    $('#username_error').text(response.errors.username);
                    $('#password_error').text(response.errors.password);
                } else if (response.status === true) {
                    $('#name').removeClass('is-invalid');
                    $('#unique_code').removeClass('is-invalid');
                    $('#username').removeClass('is-invalid');
                    $('#password').removeClass('is-invalid');
                    $('#name_error').text('');
                    $('#unique_code_error').text('');
                    $('#username_error').text('');
                    $('#password_error').text('');
                    setTimeout(() => {
                        swal({
                            title: "Berhasil",
                            text: "Akun dibuat!",
                            icon: "success",
                            timer: 1500,
                            buttons: false
                        });
                    }, 500);
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2500);
                }
            }
        });
    });

    $('#btn-reset').on('click', function () {
        $('#form-create').trigger('reset');
        $('#name').removeClass('is-invalid');
        $('#unique_code').removeClass('is-invalid');
        $('#username').removeClass('is-invalid');
        $('#password').removeClass('is-invalid');
        $('#name_error').text('');
        $('#unique_code_error').text('');
        $('#username_error').text('');
        $('#password_error').text('');
    });

    $('.form-request').on('submit', function (e) {
        e.preventDefault();
        const href = $(this).attr('action');
        $.ajax({
            type: $(this).attr('method'),
            url: href,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === true) {
                    setTimeout(() => {
                        $('.btn-progress').removeClass('btn-progress');
                        swal({
                            title: "Berhasil",
                            text: response.text,
                            icon: "success",
                            timer: 1500,
                            buttons: false
                        });
                    }, 500);
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2500);
                } else if (response.status === false) {
                    setTimeout(() => {
                        swal({
                            title: "Kesalahan",
                            text: response.text,
                            icon: "error",
                            timer: 2000,
                            buttons: false
                        });
                    }, 500);
                    $('.btn-success, .btn-primary').removeClass('btn-progress');
                }
            }
        });
    });

    $('.form-approve, #form-reject').on('submit', function (e) {
        e.preventDefault();
        const href = $(this).attr('action');
        $.ajax({
            type: $(this).attr('method'),
            url: href,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === true) {
                    $('#description').removeClass('is-invalid');
                    $('#description_error').text('');
                    $('#modal-delete').modal('hide');
                    setTimeout(() => {
                        $('.btn-progress').removeClass('btn-progress');
                        swal({
                            title: "Berhasil",
                            text: response.text,
                            icon: "success",
                            timer: 1500,
                            buttons: false
                        });
                    }, 500);
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2500);
                } else {
                    (response.errors.description) ? $('#description').addClass('is-invalid') : $('#description').removeClass('is-invalid');
                    $('#description_error').text(response.errors.description);
                }
            }
        });
    });

    $('.btn-reject').on('click', function () {
        const id = $(this).data('id');
        const user = $(this).data('user');
        const invoice = $(this).data('invoice');
        $('#id').val(id);
        $('#user').val(user);
        $('#invoice').val(invoice);
    });

    $('.btn-scan-user').on('click', function () {
        const id = $(this).data('id');
        const user = $(this).data('user');
        const transaction = $(this).data('transaction');
        $('#user_id').val(id);
        $('#user_name').text(user);
        $('#user_transaction_id').val(transaction);
    });

    $('#modal-scan-user').on('hidden.bs.modal', function () {
        $('#user_id').val('');
        $('#user_name').text('');
        $('#user_transaction_id').val('');
        $('#user_unique_code').val('');
        $('#user_unique_code').removeClass('is-invalid');
        $('#user_unique_code_error').text('');
    });

    $('#form-scan-user').on('submit', function (e) {
        e.preventDefault();
        const href = $(this).attr('action');
        $.ajax({
            type: $(this).attr('method'),
            url: href,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === 'validator') {
                    (response.errors.user_unique_code) ? $('#user_unique_code').addClass('is-invalid') : $('#user_unique_code').removeClass('is-invalid');
                    $('#user_unique_code_error').text(response.errors.user_unique_code);
                } else if (response.status === true) {
                    $('#modal-scan-user').modal('hide');
                    setTimeout(() => {
                        swal({
                            title: "Berhasil",
                            text: response.text,
                            icon: "success",
                            timer: 1500,
                            buttons: false
                        });
                    }, 500);
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2500);
                } else if (response.status === false) {
                    $('#modal-scan-user').modal('hide');
                    setTimeout(() => {
                        swal({
                            title: "Kesalahan",
                            text: response.text,
                            icon: "error",
                            timer: 2000,
                            buttons: false
                        });
                    }, 500);
                }
            }
        });
    });

    $('.btn-scan-item').on('click', function () {
        const id = $(this).data('id');
        const codename = $(this).data('codename');
        const transaction = $(this).data('transaction');
        $('#item_id').val(id);
        $('#item_name').text(codename);
        $('#item_transaction_id').val(transaction);
    });

    $('#modal-scan-item').on('hidden.bs.modal', function () {
        $('#item_id').val('');
        $('#item_name').text('');
        $('#item_transaction_id').val('');
        $('#item_unique_code').val('');
        $('#item_unique_code').removeClass('is-invalid');
        $('#item_unique_code_error').text('');
    });

    $('#form-scan-item').on('submit', function (e) {
        e.preventDefault();
        const href = $(this).attr('action');
        $.ajax({
            type: $(this).attr('method'),
            url: href,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === 'validator') {
                    (response.errors.item_unique_code) ? $('#item_unique_code').addClass('is-invalid') : $('#item_unique_code').removeClass('is-invalid');
                    $('#item_unique_code_error').text(response.errors.item_unique_code);
                    $('#btn-submit-item').removeClass('btn-progress');
                } else if (response.status === true) {
                    $('#modal-scan-item').modal('hide');
                    setTimeout(() => {
                        swal({
                            title: "Berhasil",
                            text: response.text,
                            icon: "success",
                            timer: 1500,
                            buttons: false
                        });
                    }, 500);
                    setTimeout(() => {
                        window.location.reload();
                    }, 2500);
                } else if (response.status === false) {
                    $('#btn-submit-item').removeClass('btn-progress');
                    $('#modal-scan-item').modal('hide');
                    setTimeout(() => {
                        swal({
                            title: "Kesalahan",
                            text: response.text,
                            icon: "error",
                            timer: 2000,
                            buttons: false
                        });
                    }, 500);
                }
            }
        });
    });

    $('.btn-scan-invoice').on('click', function () {
        const id = $(this).data('id');
        $('#invoice_transaction_id').val(id);
    });

    $('#modal-scan-invoice').on('hidden.bs.modal', function () {
        $('#invoice_transaction_id').val('');
        $('#invoice').val('');
        $('#invoice').removeClass('is-invalid');
        $('#invoice_error').text('');
    });

    $('#form-scan-invoice').on('submit', function (e) {
        e.preventDefault();
        const href = $(this).attr('action');
        $.ajax({
            type: $(this).attr('method'),
            url: href,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === 'validator') {
                    (response.errors.invoice) ? $('#invoice').addClass('is-invalid') : $('#invoice').removeClass('is-invalid');
                    $('#invoice_error').text(response.errors.invoice);
                } else if (response.status === true) {
                    $('#modal-scan-invoice').modal('hide');
                    setTimeout(() => {
                        swal({
                            title: "Berhasil",
                            text: response.text,
                            icon: "success",
                            timer: 1500,
                            buttons: false
                        });
                    }, 500);
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2500);
                } else if (response.status === false) {
                    $('#modal-scan-invoice').modal('hide');
                    setTimeout(() => {
                        swal({
                            title: "Kesalahan",
                            text: response.text,
                            icon: "error",
                            timer: 2000,
                            buttons: false
                        });
                    }, 500);
                    var cleaveI = new Cleave('.invoice-input', {
                        prefix: 'INV',
                        delimiter: '-',
                        blocks: [3, 6],
                        uppercase: true
                    });
                }
            }
        });
    });

    $('#modal-import').on('hidden.bs.modal', function () {
        $('#fileimport').removeClass('is-invalid').val('');
        $('#fileimport_error').text('');
    });

});
