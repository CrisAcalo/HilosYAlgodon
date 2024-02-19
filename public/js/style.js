let prevValue = "";

function validarMontoInput(input) {
    const inputValue = input.value;
    const selectionStart = input.selectionStart;

    // Verificar si se eliminó un carácter
    if (inputValue.length < prevValue.length) {
        prevValue = inputValue;
        return;
    }

    // Verificar si se intenta ingresar más de dos decimales después del punto
    const decimalIndex = inputValue.indexOf(".");
    if (decimalIndex !== -1 && inputValue.substr(decimalIndex).length > 3) {
        input.value = prevValue;
        input.setSelectionRange(selectionStart, selectionStart);
        return;
    }

    // Permitir teclas numéricas, un único punto decimal, retroceso, suprimir y flechas de navegación
    if (
        !/^\d*(\.\d{0,2})?$/.test(inputValue) || // Caracteres no permitidos
        (inputValue === "." && prevValue !== "") || // Punto al principio
        (decimalIndex !== -1 && inputValue.substr(decimalIndex).length > 3) // Más de dos decimales después del punto
    ) {
        input.value = prevValue;
        input.setSelectionRange(selectionStart, selectionStart);
    }

    prevValue = input.value;
}

// Inicializacion de librería para animaciones con scroll
AOS.init({
    // Global settings:
    disable: false, // accepts following values: 'phone', 'tablet', 'mobile', boolean, expression or function
    startEvent: "DOMContentLoaded", // name of the event dispatched on the document, that AOS should initialize on
    initClassName: "aos-init", // class applied after initialization
    animatedClassName: "aos-animate", // class applied on animation
    useClassNames: false, // if true, will add content of `data-aos` as classes on scroll
    disableMutationObserver: false, // disables automatic mutations' detections (advanced)
    debounceDelay: 50, // the delay on debounce used while resizing window (advanced)
    throttleDelay: 99, // the delay on throttle used while scrolling the page (advanced)

    // Settings that can be overridden on per-element basis, by `data-aos-*` attributes:
    offset: 120, // offset (in px) from the original trigger point
    delay: 0, // values from 0 to 3000, with step 50ms
    duration: 400, // values from 0 to 3000, with step 50ms
    easing: "ease", // default easing for AOS animations
    once: false, // whether animation should happen only once - while scrolling down
    mirror: false, // whether elements should animate out while scrolling past them
    anchorPlacement: "top-bottom", // defines which position of the element regarding to window should trigger the animation
});

$(".slider").each(function () {
    var $this = $(this);
    var $group = $this.find(".slide_group");
    var $slides = $this.find(".slide");
    var bulletArray = [];
    var currentIndex = 0;
    var timeout;

    function move(newIndex) {
        var animateLeft, slideLeft;

        advance();

        if ($group.is(":animated") || currentIndex === newIndex) {
            return;
        }

        bulletArray[currentIndex].removeClass("active");
        bulletArray[newIndex].addClass("active");

        if (newIndex > currentIndex) {
            slideLeft = "100%";
            animateLeft = "-100%";
        } else {
            slideLeft = "-100%";
            animateLeft = "100%";
        }

        $slides.eq(newIndex).css({
            display: "block",
            left: slideLeft,
        });
        $group.animate(
            {
                left: animateLeft,
            },
            function () {
                $slides.eq(currentIndex).css({
                    display: "none",
                });
                $slides.eq(newIndex).css({
                    left: 0,
                });
                $group.css({
                    left: 0,
                });
                currentIndex = newIndex;
            }
        );
    }

    function advance() {
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            if (currentIndex < $slides.length - 1) {
                move(currentIndex + 1);
            } else {
                move(0);
            }
        }, 4000);
    }

    $(".next_btn").on("click", function () {
        if (currentIndex < $slides.length - 1) {
            move(currentIndex + 1);
        } else {
            move(0);
        }
    });

    $(".previous_btn").on("click", function () {
        if (currentIndex !== 0) {
            move(currentIndex - 1);
        } else {
            move(3);
        }
    });

    $.each($slides, function (index) {
        var $button = $('<a class="slide_btn">&bull;</a>');

        if (index === currentIndex) {
            $button.addClass("active");
        }
        $button
            .on("click", function () {
                move(index);
            })
            .appendTo(".slide_buttons");
        bulletArray.push($button);
    });

    advance();
});

$(document).ready(function () {
    $(".Modern-Slider").slick({
        autoplay: true,
        autoplaySpeed: 10000,
        speed: 900,
        slidesToShow: 1,
        slidesToScroll: 1,
        pauseOnHover: false,
        dots: true,
        pauseOnDotsHover: true,
        cssEase: "linear",
        fade: true,
        draggable: false,
        prevArrow: '<button class="PrevArrow"></button>',
        nextArrow: '<button class="NextArrow"></button>',
    });

    var ventana_ancho = $(window).width();
    if (parseInt(ventana_ancho) < 700) {
        var intro = document.getElementById("owl-carousel");
        intro.style.height = "50%";
    }
    //var intro = document.getElementById('owl-carousel');
    //intro.style.height = '50%';
});

$("select#selop").on("change", function () {
    if ($(this).val() == 1) {
        $("#information-coti").text(
            "El ahorro es la acción de separar una parte de los ingresos que obtiene una persona o empresa con el fin de guardarlo para su uso en el futuro, ya sea para algún gasto previsto o imprevisto, emergencia económica o una posible inversión. Wikipedia"
        );
    } else if ($(this).val() == 2) {
        $("#information-coti").text(
            "El término inversión se refiere al acto de postergar el beneficio inmediato del bien invertido por la promesa de un beneficio futuro más o menos probable. Una inversión es una cantidad limitada de dinero que se pone a disposición de terceros, de una empresa o de un conjunto de acciones, con la finalidad de que se incremente con las ganancias que genere ese proyecto empresarial."
        );
    }
    limpiarCoti();
});

function limpiarCoti() {
    $("#uni").css("display", "none");
    $("#otro").css("display", "none");
    document.getElementById("valcot").value = "";
    document.getElementById("dateini").value = "";
}

$("#calcot").on("click", function () {
    if ($("#valcot").val() == "") {
        alert("Ingrese un monto para el cálculo");
    } else {
        var ca = document.getElementById("valcot").value;
        var inte = document.getElementById("imp").value;
        var dis = document.getElementById("diasimp").value;
        var tie = document.getElementById("dateini").value;
        if ($("#selop").val() == 1) {
            if ($("#valcot").val() < 100) {
                alert("Para inversiones el monto minimo es de $100");
            } else {
                var ld = inte / 100;
                var rss = ((ca * ld) / 363.64) * dis;
                var total = rss + parseFloat(ca);
                $("#inte").text(rss.toFixed(2));
                $("#tot").text(total.toFixed(2));

                var ldo = 5 / 100;
                var rsso = ((ca * ldo) / 363.64) * dis;
                var totalo = rsso + parseFloat(ca);
                $("#inteo").text(rsso.toFixed(2));
                $("#toto").text(totalo.toFixed(2));

                $("#uni").css("display", "");
                $("#otro").css("display", "");
            }
        }
        if ($("#selop").val() == 2) {
            if ($("#valcot").val() < 5) {
                alert("Para Ahorro mensual el monto minimo de ahorro es de $1");
            } else {
                inte = 10 / 100;
                calinteres = ahopro(ca, tie, inte);
                capitalTotal = ca * tie;
                $("#inte").text(calinteres.toFixed(2));
                $("#tot").text((capitalTotal + calinteres).toFixed(2));

                calintereso = ahopro(ca, tie, 4 / 100);
                $("#inteo").text(calintereso.toFixed(2));
                $("#toto").text((capitalTotal + calintereso).toFixed(2));

                $("#uni").css("display", "");
                $("#otro").css("display", "");
            }
        }
    }
});

function ahopro(ca, tie, inte) {
    interesMensual = inte / 12;
    interesMens = ca * interesMensual;
    res = 0;
    for (let index = 0; index < tie; index++) {
        res = (index + 1) * interesMens.toFixed(2) + res;
    }
    return res;
}

function elecciones_form_active_modal() {
    var name = document.getElementById("elecciones_form_name").value.length;
    var nCedula = document.getElementById("elecciones_form_nCedula").value
        .length;
    var nCelular = document.getElementById("elecciones_form_nCelular").value
        .length;
    var direccion = document.getElementById("elecciones_form_direccion").value
        .length;
    var correo = document.getElementById("elecciones_form_correo").value.length;

    if (
        name >= 10 &&
        nCedula == 10 &&
        nCelular >= 10 &&
        direccion >= 5 &&
        correo >= 5
    ) {
        document.getElementById("elecciones_form--hide_background").style.top =
            "0px";
        document.getElementById("modal_elecciones_form--section").style.top =
            "150px";
    } else if (name < 10) {
        alert("Ingresar nombre completo, por favor");
    } else if (nCedula < 10) {
        alert("Numero de cedula incorrecto");
    } else if (nCelular < 10) {
        alert("Numero de celular incorrecto");
    } else if (direccion < 5) {
        alert("Direccion muy corta");
    } else if (correo < 5) {
        alert("Correo muy corta");
    }
}

function elecciones_form_hide_modal() {
    document.getElementById("elecciones_form--hide_background").style.top =
        "-100vh";
    document.getElementById("modal_elecciones_form--section").style.top =
        "-100vh";
}

function elecciones_form_accept() {
    document.getElementById("elecciones_form--hide_button").disabled = true;
    document.getElementById("form_elecciones--checkbox").disabled = true;
}

$(function () {
    $("#form_elecciones--accept").on("click", function () {
        $("#form_elecciones--accept").val("Cargando...");
    });
});

function elecciones_form_No_accept() {
    document.getElementById("elecciones_form_name").value = "";
    document.getElementById("elecciones_form_nCedula").value = "";
    document.getElementById("elecciones_form_nCelular").value = "";
    document.getElementById("elecciones_form_direccion").value = "";
}

if (window.matchMedia("(min-width: 1100px)").matches) {
    $("#Productos").attr("data-aos", "zoom-in");
    $("#container-card1").attr("data-aos", "fade-left");
    $("#container-card2").attr("data-aos", "fade-right");
    $("#container-card3").attr("data-aos", "fade-left");
    $("#about-container").attr("data-aos", "fade-up");
} else {
    $("#Productos").attr("data-aos", "none");
    $("#container-card1").attr("data-aos", "none");
    $("#container-card2").attr("data-aos", "none");
    $("#container-card3").attr("data-aos", "none");
    $("#about-container").attr("data-aos", "none");
}

let Checked = null;
//The class name can vary
for (let CheckBox of document.getElementsByClassName("only-one")) {
    CheckBox.onclick = function () {
        if (Checked != null) {
            Checked.checked = false;
            Checked = CheckBox;
        }
        Checked = CheckBox;
    };
}

function updateUri() {
    let nameInput = document.getElementById("name_update_input").value;
    let pUri = document.getElementById("uri_update_input");
    let pUriValue = document.getElementById("uri_update_input_value");

    // let contentLowerCase = toLoweCamelCase(nameInput);
    let contentLowerCase = toKebabCase(nameInput);
    pUri.innerText = contentLowerCase;
    pUriValue.value = contentLowerCase;
}

function newUri() {
    let nameInput = document.getElementById("name_input").value;
    let pUri = document.getElementById("uri_input");
    let pUriValue = document.getElementById("uri_input_value");

    // let contentLowerCase = toLoweCamelCase(nameInput);
    let contentLowerCase = toKebabCase(nameInput);
    pUri.innerText = contentLowerCase;
    pUriValue.value = contentLowerCase;
}

function toLoweCamelCase(name) {
    let words = name.trim().split(/\s+/); // Divide la cadena en palabras
    let firstWord = words.shift(); // Obtén la primera palabra
    words = words.map(
        (word) => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
    ); // Capitaliza la primera letra de cada palabra y convierte el resto de la palabra en minúsculas
    let result = firstWord.toLowerCase() + words.join(""); // Une las palabras capitalizadas y convierte la primera letra de la primera palabra en minúsculas
    result = result.normalize("NFD").replace(/[\u0300-\u036f]/g, ""); // Elimina los caracteres acentuados y especiales
    result = result.replace(/[^a-zA-Z0-9]/g, ""); // Elimina todos los caracteres que no son letras o números
    return result;
}

function toKebabCase(name) {
    let words = name.trim().split(/\s+/); // Divide la cadena en palabras
    words = words.map((word) => word.toLowerCase()); // Convierte cada palabra en minúsculas
    let result = words.join("-"); // Une las palabras con guiones
    result = result.normalize("NFD").replace(/[\u0300-\u036f]/g, ""); // Elimina los caracteres acentuados y especiales
    result = result.replace(/[^a-zA-Z0-9\-]/g, ""); // Elimina todos los caracteres que no son letras, números o guiones
    return result;
}

function deployFormNewImage() {
    if (
        document.getElementById("checkbox_deploy_updateImage").checked == true
    ) {
        document.getElementById("formToUploadImage").style = "display:block";
        document.getElementById(
            "checkbox_deploy_updateExistImage"
        ).checked = false;
        document.getElementById("formToExistingImage").style = "display:none";
        document.getElementById("newImageIcon").style =
            "transform: rotate(90deg);";
        document.getElementById("existingImageIcon").style =
            "transform: rotate(0deg);";
        document
            .getElementById("nueva_imagen_producto")
            .setAttribute("required", "");
        var fields = document.getElementsByName("existing_image_update"); //no se puede usar el id para identificar porque cada uno tiene id's diferentes
        for (var i = 0; i < fields.length; i++) {
            //se usa un ciclo porque hay muchos checkbox que tienen el mismo name
            fields[i].removeAttribute("required");
            fields[i].checked = false;
        }
    } else {
        document.getElementById("formToUploadImage").style = "display:none";
        document.getElementById("newImageIcon").style =
            "transform: rotate(0deg);";
        document
            .getElementById("nueva_imagen_producto")
            .removeAttribute("required");
        document.getElementById("nueva_imagen_producto").value = "";
    }
}

function deployFormExistingImage() {
    if (
        document.getElementById("checkbox_deploy_updateExistImage").checked ==
        true
    ) {
        document.getElementById("formToExistingImage").style = "display:block";
        document.getElementById("checkbox_deploy_updateImage").checked = false;
        document.getElementById("formToUploadImage").style = "display:none";
        document.getElementById("existingImageIcon").style =
            "transform: rotate(90deg);";
        document.getElementById("newImageIcon").style =
            "transform: rotate(0deg);";

        document
            .getElementById("nueva_imagen_producto")
            .removeAttribute("required");
        document.getElementById("nueva_imagen_producto").value = "";
        var fields = document.getElementsByName("existing_image_update");
        for (var i = 0; i < fields.length; i++) {
            fields[i].setAttribute("required", "");
        }
    } else {
        document.getElementById("formToExistingImage").style = "display:none";
        document.getElementById("existingImageIcon").style =
            "transform: rotate(0deg);";
        var fields = document.getElementsByName("existing_image_update");
        for (var i = 0; i < fields.length; i++) {
            fields[i].removeAttribute("required");
            fields[i].checked = false;
        }
    }
}

function tdesModify() {
    let inputTdes = document.getElementById("tdesContainer");
    let checkboxTdes = document.getElementById("tdesCheckbox");
    let inputTitle = document.getElementById("titleCardInput");
    let inputDes = document.getElementById("desCardInput");

    if (checkboxTdes.checked == true) {
        inputTdes.style = "display:block";
        inputTitle.setAttribute("required", "");
        inputDes.setAttribute("required", "");
    } else {
        inputTdes.style = "display:none";
        inputTitle.removeAttribute("required");
        inputDes.removeAttribute("required");
        inputTitle.value = null;
        inputDes.value = null;
    }
}

function tlinkModify() {
    let inputTlink = document.getElementById("tlinkContainer");
    let inputsRequired = document.getElementById("tlinkInput");
    if (document.getElementById("tlinkCheckbox").checked == true) {
        inputTlink.style = "display:block";
        inputsRequired.setAttribute("required", "");
    } else {
        inputTlink.style = "display:none";
        inputsRequired.removeAttribute("required");
        inputsRequired.value = null;
    }
}

function tdesUpdateModify(title, description) {
    let inputTdes = document.getElementById("tdesContainer");
    let checkboxTdes = document.getElementById("tdesCheckbox");
    let inputTitle = document.getElementById("titleCardInput");
    let inputDes = document.getElementById("desCardInput");

    if (checkboxTdes.checked == true) {
        inputTdes.style = "display:block";
        inputTitle.setAttribute("required", "");
        inputDes.setAttribute("required", "");
        inputTitle.value = title;
        inputDes.value = description;
    } else {
        inputTdes.style = "display:none";
        inputTitle.removeAttribute("required");
        inputDes.removeAttribute("required");
        inputTitle.value = null;
        inputDes.value = null;
    }
}

function tlinkUpdateModify(link) {
    let inputTlink = document.getElementById("tlinkContainer");
    let inputsRequired = document.getElementById("tlinkInput");
    if (document.getElementById("tlinkCheckbox").checked == true) {
        inputTlink.style = "display:block";
        inputsRequired.setAttribute("required", "");
        inputsRequired.value = link;
    } else {
        inputTlink.style = "display:none";
        inputsRequired.removeAttribute("required");
        inputsRequired.value = null;
    }
}

function positionValidation(positions) {
    pValidatePosition = document.getElementById("textPositionValidation");
    if (
        positions.includes(
            parseInt(document.getElementById("positionNewAcRapido").value)
        )
    ) {
        pValidatePosition.innerText = "La posición ingresada ya está en uso";
        pValidatePosition.style = "display:block;max-width:max-content;";
        document.getElementById("buttonNewAcRapido").disabled = true;
    } else {
        pValidatePosition.innerText = "";
        pValidatePosition.style = "display:none";
        document.getElementById("buttonNewAcRapido").disabled = false;
    }
}

function posicionReplaceValidation(positions, originalPosition) {
    pValidatePosition = document.getElementById("textPositionValidation");

    var newPosition = document.getElementById("positionNewAcRapido").value;
    var positionIndex = -1;

    positions.forEach(function (item, index) {
        if (item.position == newPosition) {
            positionIndex = index;
        }
    });

    if (originalPosition == newPosition) {
        pValidatePosition.innerText =
            "La posición ingresada ya está en uso. El acceso rápido";
        pValidatePosition.style = "display:none";
    } else if (positionIndex > -1) {
        pValidatePosition.innerText =
            'La posición ingresada ya está en uso.\n El acceso rápido "' +
            positions[positionIndex].name +
            '" pasará de la posición ~ ' +
            positions[positionIndex].position +
            " ~ a la posición ~ " +
            originalPosition +
            " ~";
        pValidatePosition.style = "display:block;max-width:max-content;";
    } else {
        pValidatePosition.innerText = "";
        pValidatePosition.style = "display:none";
    }
}

function showAdminSidebar() {
    document.getElementById("sidebar").style = "margin-left: 0px;";
    document.getElementById("button_sideBar").style = "display:none;";
    document.getElementById("button_close_sideBar").style =
        "margin-left:0px;display:block;";
    document.getElementById("sidebar_container").style = "margin-left:0px;";
}

function closeAdminSidebar() {
    document.getElementById("sidebar").style = "margin-left: -280px;";
    document.getElementById("button_sideBar").style = "display:block;";
    document.getElementById("button_close_sideBar").style =
        "margin-left:-280px;display:none;";
    document.getElementById("sidebar_container").style = "margin-left:-280px;";
}
