document.getElementById("btn_login").addEventListener("click", (Event) =>{
    let user = document.getElementById("Correo_input").value;
    let pass = document.getElementById("password_input").value;

    if (user == "admin" || pass == "admin"){
        window.location.href = 'Dashboard.html';
    }
});