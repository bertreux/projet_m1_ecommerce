function deleteRows() {
    var table = document.getElementById("myTable");
    var rowCount = table.rows.length;
    var tableauId = {};
    var a=0;
    for (var i = rowCount - 1; i > 0; i--) {
        var row = table.rows[i];
        var checkBox = row.cells[0].getElementsByTagName("input")[0];

        if (checkBox.checked) {
            tableauId[a]=row.cells[1].innerHTML;
            a++;
        }
    }
    var nomObjet = document.getElementById('NomObjet').value;
    var url = document.getElementById('Url').value;

    const data = {
        parametre1: tableauId,
        parametre2: nomObjet
    };

    var myInit = { method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data) };

    fetch(url, myInit)
        .then(function(response) {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error("Erreur lors de la requête");
            }
        })
        .then(function(data) {
            if(data === 1){
                console.log('Element bien supprimer de la base')
                location.reload();
            }else {
                alert(data)
                throw new Error("Erreur lors de la requête");
            }
        })
        .catch(function(error) {
            console.log(error);
        });
}

function selectAll(checkAll) {
    var table = document.getElementById("myTable");
    var rowCount = table.rows.length;

    for (var i = rowCount - 1; i > 0; i--) {
        var row = table.rows[i];
        var checkBox = row.cells[0].getElementsByTagName("input")[0];

        if (checkBox.checked == false && checkAll.checked == true) {
            checkBox.checked = true;
            document.getElementById('btn-delete-row').style.display = 'block';
        }
        if(checkBox.checked == true && checkAll.checked == false) {
            checkBox.checked = false;
            document.getElementById('btn-delete-row').style.display = 'none';
        }
    }
}

function selectOne() {
    var table = document.getElementById("myTable");
    var rowCount = table.rows.length;
    var nbCheck = 0;

    for (var i = rowCount - 1; i > 0; i--) {
        var row = table.rows[i];
        var checkBox = row.cells[0].getElementsByTagName("input")[0];

        if (checkBox.checked) {
            nbCheck++;
        }
    }
    rowCount--;
    if(nbCheck == rowCount){
        document.getElementById('checkboxAll').checked = true;
    }else{
        document.getElementById('checkboxAll').checked = false;
    }
    if(nbCheck > 0){
        document.getElementById('btn-delete-row').style.display = 'block';
    }else{
        document.getElementById('btn-delete-row').style.display = 'none';
    }
}

function imageProduit(){
    if(document.getElementById("image_produit").value != ""){
        document.getElementById("image_categorie").style.display = "none";
        document.getElementById("image_categorie").value = null;
    }else{
        document.getElementById("image_categorie").style.display = null;
    }
}
function imageCategorie(){
    if(document.getElementById("image_categorie").value != ""){
        document.getElementById("image_produit").style.display = "none";
        document.getElementById("image_produit").value = null;
    }else{
        document.getElementById("image_produit").style.display = null;
    }
}
