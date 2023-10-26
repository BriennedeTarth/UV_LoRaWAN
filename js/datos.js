function obtenerdatos(fechas, colecion, type_dato) {
  return new Promise(function (resolve, reject) {
    var arregloJSON = JSON.stringify(fechas);

    // Crear la solicitud AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "./conexion_mongodb/obtenerdatos.php?coleccion=" + colecion + "&tipo_dato=" + type_dato, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Definir la función de callback
    var respuesta;
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          // Obtener la respuesta del servidor
          respuesta = JSON.parse(xhr.responseText);
          resolve(respuesta);
        } else {
          reject(xhr.statusText);
        }
      }
    };
    xhr.send("datos=" + arregloJSON);
  });
}

function graficar(fechas, colecion, type_dato) {
  obtenerdatos(fechas, colecion, type_dato)
    .then(function (respuesta) {
      var clavesP = Object.keys(respuesta);
      const contenedor = document.getElementById('graphics');
      if (clavesP.length > 0) {
        const create_grafico = document.createElement("canvas");
        create_grafico.id = "myChart"
        contenedor.appendChild(create_grafico);
        const grafico = document.getElementById('myChart');
        const message = document.getElementById('message');
        if (message && message.parentNode === contenedor) {
          contenedor.removeChild(message);
        }
        let etiquetas;
        var datosExterno = [];
        var primeraClave = clavesP[0];
        var primerObjeto = respuesta[primeraClave];
        // Obtener la primera clave del objeto interno
        var primeraClaveInterna = Object.keys(primerObjeto)[0];
        for (var key in respuesta) {
          if (respuesta.hasOwnProperty(key)) {
            var objetoInterno = respuesta[key];
            etiquetas = objetoInterno.time;

            var color = generarColorRGBA();
            var datos = {
              label: key,
              data: respuesta[key][primeraClaveInterna],
              backgroundColor: [color],
              borderColor: [color],
              borderWidth: 2
            };
            datosExterno.push(datos);
          }
        }
        new Chart(grafico, {
          type: 'line',
          data: {
            labels: etiquetas,
            datasets: datosExterno
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      } else {
        const grafico = document.querySelectorAll('#myChart');
        grafico.forEach(function (elemento) {
          elemento.parentNode.removeChild(elemento);
        });
        const create_message = document.createElement("h2");
        create_message.id = "message"
        create_message.textContent = "No date selected";
        contenedor.appendChild(create_message);
      }



    })
    .catch(function (error) {
      console.error("Error al obtener los datos: " + error);
    });
}

function generarColorRGBA() {
  var a = Math.floor(Math.random() * 256); // Valor aleatorio para 'a'
  var b = Math.floor(Math.random() * 256); // Valor aleatorio para 'b'
  var c = Math.floor(Math.random() * 256); // Valor aleatorio para 'c'

  var colorRGBA = "rgba(" + a + ", " + b + ", " + c + ", 0.5)";
  return colorRGBA;
}

/*Exportar a CSV*/

function convertToCSV(objArray) {
  const array = typeof objArray !== 'object' ? JSON.parse(objArray) : objArray;
  let str = '';

  for (let i = 0; i < array.length; i++) {
    let line = '';
    for (let index in array[i]) {
      if (line !== '') line += ',';
      line += array[i][index];
    }
    str += line + '\r\n';
  }

  return str;
}

function exportCSVFile(headers, items, fileTitle) {
  if (headers) {
    items.unshift(headers);
  }

  const csv = convertToCSV(items);
  const exportedFilenmae = fileTitle + '.csv' || 'export.csv';

  const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
  if (navigator.msSaveBlob) {
    navigator.msSaveBlob(blob, exportedFilenmae);
  } else {
    const link = document.createElement('a');
    if (link.download !== undefined) {
      const url = URL.createObjectURL(blob);
      link.setAttribute('href', url);
      link.setAttribute('download', exportedFilenmae);
      link.style.visibility = 'hidden';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }
  }
}

// Ejemplo de uso
function guardararchivoCSV(fechas, colecion, type_dato) {
  obtenerdatos(fechas, colecion, type_dato)
    .then(function (respuesta) {
      var fechas = Object.keys(respuesta);
      if (fechas.length > 0) {
        var datosCSV = [];
        var primeraClave = fechas[0];
        var primerObjeto = respuesta[primeraClave];
        // Obtener la primera clave del objeto interno
        var headers = Object.keys(primerObjeto);
        headers.unshift('date');
        for (var key in respuesta) {
          if (respuesta.hasOwnProperty(key)) {
            for (let i = 0; i < respuesta[key][headers[1]].length; i++) {
              var datos = {};
              for (let i = 0; i < headers.length; i++) {
                datos[headers[i]] = undefined;
              }
              datos[headers[0]] = key;
              datos[headers[1]] = respuesta[key][headers[1]][i];
              datos[headers[2]] = respuesta[key][headers[2]][i];
              datosCSV.push(datos);
            }
          }
        }
        exportCSVFile(headers, datosCSV, 'sl-m');
      } else {
        /*Mensaje en caso de no tener datos*/
        Swal.fire({
          icon: 'error',
          text: "Please select a date.",
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Accept"
        });
      }
    })
    .catch(function (error) {
      console.error("Error al obtener los datos: " + error);
    });
}