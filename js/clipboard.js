/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

(function($) {
    $.pasteImage = function(callback) {
        var allowPaste = true;
        var foundImage = false;
        if(typeof(callback) == "function") {
            $.event.props.push('clipboardData');
            $(document).bind("paste", doPaste);
            if (!window.Clipboard) {
		var pasteCatcher = $(document.createElement("div"));
		pasteCatcher.attr("contenteditable","true").css({"position" : "absolute", "left" : "-999", width : "0", height : "0", "overflow" : "hidden", outline : 0});
		$(document.body).prepend(pasteCatcher);
            }
	}
    
        function doPaste(e) {
            if (allowPaste === true) {
                if (e.clipboardData.items) {
                    var items = e.clipboardData.items;
                    if (items) {
                        for (var i = 0; i < items.length; i++) {
                            if (items[i].type.indexOf("image") !== -1) {
                                var blob = items[i].getAsFile();
                                var reader = new FileReader();
                                reader.onload = function(event) {
                                    callback(event.target.result); //base64
                                };
                                reader.readAsDataURL(blob);
                            }
                        }
                    } else {
                        alert('No se encontraron elementos en el portapapeles.');
                    }
                } else {
                    pasteCatcher.get(0).focus();
                    foundImage = true;
                    setTimeout(checkInput, 100);
                }
            }
        }

        function checkInput() {
            if(foundImage == true) {
                var child = pasteCatcher.children().last().get(0);
                if (child) {
                    if (child.tagName === "IMG" && child.src.substr(0, 5) === 'data:') {
                        callback(child.src);
                        foundImage = false;
                    } else {
                        alert("No es una imagen!");
                    }
                    pasteCatcher.html("");
                } else {
                    alert("No se encontraron hijos en el DIV.");
                }
            } else {
                alert('No se encontró imagen en el portapapeles.');
            }
        }
    };
})(jQuery);