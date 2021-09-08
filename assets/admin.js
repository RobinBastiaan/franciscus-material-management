/*
 * Welcome to the admins main JavaScript file!
 */

// any CSS you import will output into a single css file (admin.css in this case)
import './styles/admin.scss';

const $ = require('jquery');
window.jQuery = $;

$('td.js-row-action').each(function () {
    $(this).find('span').html('<a href="' +
        $(this).siblings('td.actions').find('a.action-edit').attr('href') + ' ">' +
        $(this).find('span').text() + '</a>');
});
