function numero(event) {
    var char = event.which;
    if (char !== 8 && char !== 9 && char !== 86 && (char < 45 || char > 57) && (char < 96 || char > 105) && (char < 33 || char > 40)) {
        return false;
    }
}
function decimal(event) {
    var char = event.which;
    if (char !== 8 && char !== 9 && char !== 86 && char !== 110 && char !== 190 && (char < 45 || char > 57) && (char < 96 || char > 105) && (char < 33 || char > 40)) {
        return false;
    }
}
function post(url, params) {
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", url);
    for(var key in params) {
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", key);
        hiddenField.setAttribute("value", params[key]);

        form.appendChild(hiddenField);
    }
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
function years(year) {
    var date = new Date(year, 0, 1);
    var fin = new Date();
    if (typeof aprobado !== 'undefined') {
        fin = new Date(aprobado.substr(0, 4), aprobado.substr(5), 1);
    }
    var options = '';
    while (date <= fin) {
        options += '<option value="' + date.getFullYear() + '">' + date.getFullYear() + '</option>';
        date.setYear(date.getFullYear() + 1);
    }
    return options;
}
function months(year, month) {
    var monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    month--;
    var date = new Date(year, month, 1);
    var fin = new Date();
    if (typeof aprobado !== 'undefined') {
        fin = new Date(aprobado.substr(0, 4), aprobado.substr(5) - 1, 1);
    }
    var options = '';
    if (date.getFullYear() < fin.getFullYear()) {
        fin = new Date(year, 11, 1);
    }
    while (date <= fin) {
        options += '<option value="' + (date.getMonth() + 1) + '">' + monthNames[date.getMonth()] + '</option>';
        date.setMonth(date.getMonth() + 1);
    }
    return options;
}