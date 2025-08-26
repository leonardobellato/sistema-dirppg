function mascara(i){
    // Função de máscara de CPF
    var v = i.value;
    
    if(isNaN(v[v.length-1])){
        i.value = v.substring(0, v.length-1);
        return;
    }
    
    i.setAttribute("maxlength", "14");
    if (v.length == 3 || v.length == 7) i.value += ".";
    if (v.length == 11) i.value += "-";
}

// Alternância abas
const toggleOptions = document.querySelectorAll(".toggle-option");
const contents = document.querySelectorAll(".content");

toggleOptions.forEach((option, index) => {
    option.addEventListener("click", () => {
        toggleOptions.forEach(opt => opt.classList.remove("selected"));
        option.classList.add("selected");

        contents.forEach(cnt => cnt.classList.remove("active"));
        contents[index].classList.add("active");
    });
});