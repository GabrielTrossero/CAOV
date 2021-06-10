let mensajeBackup = $("#mensaje-backup");
mensajeBackup.hide();
let botonBackup = $("#backup");

botonBackup.on("click", function (event) {
    let token = $("#token").val();
    botonBackup.hide();
    mensajeBackup.show();

    $.post("/administrador/backup", {
            _token: token
        })
        .done(data => {
            let message = data.success 
                ? "El Backup se ha llevado a cabo con éxito!"
                : "ERROR: no se ha podido realizar el Backup.";
            alert(message);
            mensajeBackup.hide();
            botonBackup.show();

        })
        .catch(error => console.log(error));
});
