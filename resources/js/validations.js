console.log("asdd22s");
function validarAlfabeticos(inputElement) {
    const valor = inputElement.value;

    // Permitir borrar y teclas de flechas
    if (/^[a-zA-Z\b]+$/.test(valor)) {
        inputElement.classList.remove("is-invalid");
    } else {
        inputElement.value = valor.replace(/[^a-zA-Z]/g, ""); // Eliminar caracteres no alfabéticos
        inputElement.classList.add("is-invalid");
    }
}
console.log("asdds");