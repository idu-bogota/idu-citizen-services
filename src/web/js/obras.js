function LoadProjects(id) {
    $.post("WebService1.asmx/WS_ContratosGrupo", { IdGrupo: id })
    .done(function (data) {
        var proyectos = data.childNodes[0].innerHTML.split(',');
        $.each(proyectos, function (data, i) {
            $("#proj" + id).append('<a id="' + i + '">' + i + '</a>');
        });
    });
}
 
function LoadInfoProyect(cont) {
    $.post("WebService1.asmx/InfoContrato", { contrato: cont })
    .done(function (data) {
        var info = data.childNodes[0].innerHTML.split(';');
    $("#info_contrato").html("");
    $("#mapa").remove();
    var src = 'http://a1136:8080/geoserver/julian/wms?service=WMS&version=1.1.0&request=GetMap&layers=julian:ObrasXParam&styles=&bbox=-74.662247,3.686808,-73.885450,4.898138&width=334&height=512&srs=EPSG:4326&format=application/openlayers&viewparams=cont:' + cont;
    $("#visor").append('<iframe name="mapa" id="mapa" style="border: none;" height="600" width="800" src="' + src + '"></iframe>');
    $.each(info, function (data, i) {
        $("#info_contrato").append(i + '<br>');
    });
    });
}

$(document).ready(function () {
    $(".collapse").collapse()
    $("#accordion").accordion();
    for (i = 1; i < 7; i++)
        LoadProjects(i);
    $(document).on("click", "a", function () {
        LoadInfoProyect($(this).attr("id"));
    });
});